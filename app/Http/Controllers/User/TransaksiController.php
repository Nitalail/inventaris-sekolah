<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('peminjaman')
            ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->leftJoin('kategoris', 'barang.kategori_id', '=', 'kategoris.id')
            ->leftJoin('ruangan', 'barang.ruangan_id', '=', 'ruangan.id')
            ->select(
                'barang.kode as kode_barang',
                'barang.nama as nama_barang',
                'peminjaman.id',
                'users.name as peminjam',
                'peminjaman.tanggal_pinjam',
                'peminjaman.tanggal_kembali',
                'peminjaman.status',
                'peminjaman.sub_barang_ids',
                'peminjaman.keperluan',
                'peminjaman.catatan',
                'kategoris.nama as kategori_nama',
                'ruangan.nama_ruangan'
            );

        // Apply filters based on URL parameters for smart navigation
        if ($request->get('filter')) {
            $filter = $request->get('filter');
            switch ($filter) {
                case 'inventory':
                    // Focus on transactions related to inventory reports
                    break;
                case 'transaction':
                    // Already showing all transactions
                    break;
                case 'room':
                    // Filter by room if specified
                    if ($request->get('room_id')) {
                        $query->where('barang.ruangan_id', $request->get('room_id'));
                    }
                    break;
            }
        }

        // Filter by status (for overdue notifications, etc.)
        if ($request->get('status')) {
            $status = $request->get('status');
            if ($status === 'terlambat') {
                $query->where('peminjaman.status', 'dipinjam')
                      ->where('peminjaman.tanggal_kembali', '<', now());
            } else {
                $query->where('peminjaman.status', $status);
            }
        }

        // Filter by condition (for critical items)
        if ($request->get('condition')) {
            $condition = $request->get('condition');
            if ($condition === 'rusak_berat') {
                $query->whereExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                             ->from('sub_barang')
                             ->whereColumn('sub_barang.barang_id', 'barang.id')
                             ->where('sub_barang.kondisi', 'rusak_berat');
                });
            }
        }

        // Filter by stock status
        if ($request->get('stock')) {
            $stock = $request->get('stock');
            if ($stock === 'low') {
                $query->whereExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                             ->from('barang as b2')
                             ->whereColumn('b2.id', 'barang.id')
                             ->whereRaw('(SELECT COUNT(*) FROM sub_barang WHERE sub_barang.barang_id = b2.id) <= 2');
                });
            }
        }

        $transaksis = $query->orderBy('peminjaman.created_at', 'desc')->paginate(10);

        // Store highlight parameter for frontend
        $highlightId = $request->get('highlight');

        // Add sub barang codes to each transaction
        $transaksis->each(function ($transaksi) {
            if ($transaksi->sub_barang_ids) {
                $subBarangIds = json_decode($transaksi->sub_barang_ids, true);
                if ($subBarangIds) {
                    $subBarangCodes = DB::table('sub_barang')
                        ->whereIn('id', $subBarangIds)
                        ->pluck('kode')
                        ->toArray();
                    $transaksi->sub_barang_codes = $subBarangCodes;
                } else {
                    $transaksi->sub_barang_codes = [];
                }
            } else {
                $transaksi->sub_barang_codes = [];
            }
        });

        return view('admin.transaksi', compact('transaksis', 'highlightId'));
    }

    public function getDetail($id)
    {
        $transaksi = DB::table('peminjaman')
            ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->select(
                'barang.kode as kode_barang',
                'barang.nama as nama_barang',
                'peminjaman.id',
                'users.name as peminjam',
                'peminjaman.tanggal_pinjam',
                'peminjaman.tanggal_kembali',
                'peminjaman.status',
                'peminjaman.sub_barang_ids',
                'peminjaman.keperluan',
                'peminjaman.catatan'
            )
            ->where('peminjaman.id', $id)
            ->first();

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        // Get sub barang codes
        $subBarangCodes = [];
        if ($transaksi->sub_barang_ids) {
            $subBarangIds = json_decode($transaksi->sub_barang_ids, true);
            if ($subBarangIds) {
                $subBarangCodes = DB::table('sub_barang')
                    ->whereIn('id', $subBarangIds)
                    ->pluck('kode')
                    ->toArray();
            }
        }

        $transaksi->sub_barang_codes = $subBarangCodes;

        return response()->json([
            'success' => true,
            'transaksi' => $transaksi
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1|max:5',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'keperluan' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // 1. Cek stok tersedia dari sub barang excluding borrowed items
            $barang = Barang::withCount(['subBarang as available_stock' => function ($query) {
                $query->whereIn('kondisi', ['baik', 'rusak_ringan'])
                      ->whereNotExists(function ($subQuery) {
                          $subQuery->select(DB::raw(1))
                                   ->from('peminjaman')
                                   ->whereRaw('JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))')
                                   ->whereIn('peminjaman.status', ['pending', 'dipinjam', 'dikonfirmasi']);
                      });
            }])->find($validated['barang_id']);

            if (!$barang) {
                throw new \Exception('Barang tidak ditemukan');
            }

            if ($barang->available_stock < $validated['jumlah']) {
                throw new \Exception('Stok barang tidak mencukupi. Tersedia: ' . $barang->available_stock);
            }

            // 2. Note: Sub barang assignment should be handled here
            // For now, we create the transaction but don't assign specific sub barang items
            // This can be handled during admin approval/processing

            // 3. Simpan peminjaman
            DB::table('peminjaman')->insert([
                'user_id' => auth()->id(),
                'barang_id' => $validated['barang_id'],
                'jumlah' => $validated['jumlah'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali' => $validated['tanggal_kembali'],
                'keperluan' => $validated['keperluan'],
                'catatan' => $validated['catatan'] ?? null,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Peminjaman berhasil diajukan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengajukan peminjaman: '.$e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,dipinjam,dikembalikan,terlambat,rusak',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $transaksi = DB::table('peminjaman')->where('id', $id)->first();
            
            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan');
            }

            // 🔒 PROTEKSI: Transaksi yang sudah dikembalikan tidak bisa diubah
            if ($transaksi->status === 'dikembalikan') {
                throw new \Exception('Transaksi yang sudah dikembalikan tidak dapat diubah lagi. Status sudah final.');
            }

            // 🔒 PROTEKSI: Jika ingin mengubah dari status lain ke dikembalikan, catat waktu pengembalian
            $updateData = [
                'status' => $validated['status'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali' => $validated['tanggal_kembali'],
                'catatan' => $validated['catatan'] ?? null,
                'updated_at' => now(),
            ];

            // Jika status berubah menjadi dikembalikan, catat waktu pengembalian aktual
            if ($validated['status'] === 'dikembalikan' && $transaksi->status !== 'dikembalikan') {
                $updateData['tanggal_kembali_aktual'] = now();
                $updateData['catatan'] = ($validated['catatan'] ?? '') . ' [Dikembalikan pada: ' . now()->format('d/m/Y H:i') . ']';
            }

            DB::table('peminjaman')->where('id', $id)->update($updateData);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil diperbarui.',
                    'data' => array_merge($updateData, ['id' => $id])
                ]);
            }

            return redirect()->back()->with('success', 'Transaksi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update Transaksi Error: '.$e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: '.$e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $transaksi = DB::table('peminjaman')->where('id', $id)->first();
            
            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan');
            }

            // 🔒 PROTEKSI: Transaksi yang sudah dikembalikan tidak bisa dihapus
            if ($transaksi->status === 'dikembalikan') {
                throw new \Exception('Transaksi yang sudah dikembalikan tidak dapat dihapus. Data sudah final untuk keperluan audit.');
            }

            // Hapus transaksi (hanya untuk status: pending, dipinjam, terlambat, rusak)
            DB::table('peminjaman')->where('id', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus dan stok dikembalikan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delete Transaksi Error: '.$e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage()
            ], 500);
        }
    }
}