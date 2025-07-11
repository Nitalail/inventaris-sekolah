<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubBarang;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class SubBarangController extends Controller
{
    public function index()
    {
        $subBarangs = SubBarang::with('barang')->paginate(10);
        return view('admin.sub-barang.index', compact('subBarangs'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('admin.sub-barang.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'kode' => 'required|unique:sub_barang,kode|max:50',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,nonaktif',
            'tahun_perolehan' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        SubBarang::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sub barang berhasil ditambahkan!'
            ]);
        }

        return redirect()->back()->with('success', 'Sub barang berhasil ditambahkan!');
    }

    public function show($id)
    {
        $subBarang = SubBarang::with('barang')->findOrFail($id);
        return view('admin.sub-barang.show', compact('subBarang'));
    }

    public function edit($id)
    {
        $subBarang = SubBarang::findOrFail($id);
        $barangs = Barang::all();
        return view('admin.sub-barang.edit', compact('subBarang', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $subBarang = SubBarang::findOrFail($id);
        
        try {
            $validated = $request->validate([
                'barang_id' => 'required|exists:barang,id',
                'kode' => 'required|max:50|unique:sub_barang,kode,' . $id,
                'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,nonaktif',
                'tahun_perolehan' => 'required|integer|min:1900|max:' . date('Y'),
            ]);

            // Convert tahun_perolehan to integer if it's a string
            if (isset($validated['tahun_perolehan']) && is_string($validated['tahun_perolehan'])) {
                $validated['tahun_perolehan'] = (int) $validated['tahun_perolehan'];
            }

            $subBarang->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sub barang berhasil diperbarui!'
                ]);
            }

            return redirect()->back()->with('success', 'Sub barang berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }

            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            throw $e;
        }
    }

    public function destroy(Request $request, $id)
    {
        $subBarang = SubBarang::findOrFail($id);
        $subBarang->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sub barang berhasil dihapus!'
            ]);
        }

        return redirect()->back()->with('success', 'Sub barang berhasil dihapus!');
    }

    public function getByBarang($barangId)
    {
        $subBarangs = SubBarang::where('barang_id', $barangId)
            ->select('sub_barang.*')
            ->selectRaw('
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM peminjaman 
                        WHERE JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))
                        AND peminjaman.status IN ("pending", "dipinjam", "dikonfirmasi")
                    ) THEN "dipinjam"
                    ELSE "tersedia"
                END as status_peminjaman
            ')
            ->selectRaw('
                (SELECT CONCAT(users.name, " (", peminjaman.status, ")") 
                 FROM peminjaman 
                 JOIN users ON peminjaman.user_id = users.id
                 WHERE JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))
                 AND peminjaman.status IN ("pending", "dipinjam", "dikonfirmasi")
                 LIMIT 1) as peminjam_info
            ')
            ->get();
            
        return response()->json($subBarangs);
    }

    public function getAvailableByBarang($barangId)
    {
        $subBarangs = SubBarang::where('barang_id', $barangId)
            ->whereIn('kondisi', ['baik', 'rusak_ringan'])
            ->whereNotExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                         ->from('peminjaman')
                         ->whereRaw('JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))')
                         ->whereIn('peminjaman.status', ['pending', 'dipinjam', 'dikonfirmasi']);
            })
            ->get(['id', 'kode', 'kondisi', 'tahun_perolehan']);
        
        return response()->json($subBarangs);
    }

    public function testUpdate(Request $request, $id)
    {
        try {
            $subBarang = SubBarang::findOrFail($id);
            
            // Test update dengan data sederhana
            $testData = [
                'barang_id' => $subBarang->barang_id,
                'kode' => $subBarang->kode,
                'kondisi' => 'nonaktif',
                'tahun_perolehan' => $subBarang->tahun_perolehan
            ];

            $subBarang->update($testData);

            return response()->json([
                'success' => true,
                'message' => 'Test update berhasil',
                'sub_barang' => $subBarang->fresh()->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test update gagal: ' . $e->getMessage()
            ], 500);
        }
    }
} 