<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\SubBarang;
use App\Models\Peminjaman;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melihat peminjaman.');
        }

        $peminjaman = DB::table('peminjaman as p')
            ->join('barang as i', 'p.barang_id', '=', 'i.id')
            ->select(
                'p.*',
                'i.nama as nama_barang',
                'i.kategori',
                'i.kondisi'
            )
            ->where('p.user_id', Auth::id())
            ->orderBy('p.created_at', 'desc')
            ->get();

        return view('user.peminjaman.index', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk mengajukan peminjaman.'
            ], 401);
        }

        try {
            Log::info('Peminjaman request data:', $request->all());

            $validated = $request->validate([
                'barangId' => 'required|exists:barang,id',
                'subBarangIds' => 'required|array|min:1|max:10',
                'subBarangIds.*' => 'required|integer|exists:sub_barang,id',
                'quantity' => 'required|integer|min:1|max:10',
                'startDate' => 'required|date|after_or_equal:today',
                'endDate' => 'required|date|after:startDate',
                'purpose' => 'required|string|min:3|max:500',
            ]);

            Log::info('Validation passed:', $validated);

            $barang = DB::table('barang')->where('id', $validated['barangId'])->first();

            if (!$barang) {
                Log::warning('Barang not found:', ['barangId' => $validated['barangId']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ], 404);
            }

            Log::info('Barang found:', ['barang' => $barang]);

            // Validate that selected sub barang belong to the specified barang and are available
            $selectedSubBarang = \App\Models\SubBarang::whereIn('id', $validated['subBarangIds'])
                ->where('barang_id', $validated['barangId'])
                ->whereIn('kondisi', ['baik', 'rusak_ringan'])
                ->where('bisa_dipinjam', true)
                ->whereNotExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                             ->from('peminjaman')
                             ->whereRaw('JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))')
                             ->whereIn('peminjaman.status', ['pending', 'dipinjam', 'dikonfirmasi']);
                })
                ->get();

            if ($selectedSubBarang->count() !== count($validated['subBarangIds'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa item yang dipilih tidak valid atau tidak tersedia'
                ], 400);
            }

            // Verify quantity matches selected items
            if ($validated['quantity'] !== count($validated['subBarangIds'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah item tidak sesuai dengan yang dipilih'
                ], 400);
            }

            $activeBorrow = DB::table('peminjaman')
                ->where('user_id', Auth::id())
                ->where('barang_id', $validated['barangId'])
                ->whereIn('status', ['pending', 'approved', 'borrowed'])
                ->exists();

            if ($activeBorrow) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda masih memiliki peminjaman aktif untuk barang ini'
                ], 400);
            }

            $startDate = Carbon::parse($validated['startDate']);
            $endDate = Carbon::parse($validated['endDate']);
            $duration = $startDate->diffInDays($endDate);

            if ($duration > 30) {
                return response()->json([
                    'success' => false,
                    'message' => 'Durasi peminjaman maksimal 30 hari'
                ], 400);
            }

            Log::info('About to insert peminjaman data');

            DB::beginTransaction();

            try {
                $peminjamanId = DB::table('peminjaman')->insertGetId([
                    'user_id' => Auth::id(),
                    'barang_id' => $validated['barangId'],
                    'sub_barang_ids' => json_encode($validated['subBarangIds']),
                    'jumlah' => $validated['quantity'],
                    'tanggal_pinjam' => $validated['startDate'],
                    'tanggal_kembali_rencana' => $validated['endDate'],
                    'tanggal_kembali' => $validated['endDate'],
                    'keperluan' => $validated['purpose'],
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info('Peminjaman inserted with ID:', ['id' => $peminjamanId]);

                // Note: Sub barang assignment should be handled here
                // For now, we just create the borrowing request
                // Sub barang items can be assigned during admin approval

                try {
                    $subBarangCodes = $selectedSubBarang->pluck('kode')->join(', ');
                    DB::table('activity_logs')->insert([
                        'user_id' => Auth::id(),
                        'action' => 'create_borrow_request',
                        'description' => 'Mengajukan peminjaman untuk ' . $barang->nama . ' (Item: ' . $subBarangCodes . ')',
                        'created_at' => now(),
                    ]);
                } catch (\Exception $logError) {
                    Log::warning('Failed to insert activity log:', ['error' => $logError->getMessage()]);
                }

                DB::commit();

                // Create notification for admin
                try {
                    $peminjaman = Peminjaman::with(['user', 'barang'])->find($peminjamanId);
                    if ($peminjaman) {
                        Notification::createPeminjamanNotification($peminjaman, 'peminjaman_baru');
                        Log::info('Notification created for peminjaman:', ['peminjaman_id' => $peminjamanId]);
                    }
                } catch (\Exception $notifError) {
                    Log::warning('Failed to create notification:', ['error' => $notifError->getMessage()]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan peminjaman berhasil diajukan',
                    'data' => [
                        'id' => $peminjamanId,
                        'item_name' => $barang->nama,
                        'selected_sub_barang' => $selectedSubBarang->pluck('kode')->toArray(),
                        'quantity' => $validated['quantity'],
                        'start_date' => $validated['startDate'],
                        'end_date' => $validated['endDate'],
                        'status' => 'pending'
                    ]
                ], 201);

            } catch (\Exception $dbError) {
                DB::rollBack();
                throw $dbError;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating borrow request:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $peminjaman = DB::table('peminjaman as p')
            ->join('barang as i', 'p.barang_id', '=', 'i.id')
            ->select(
                'p.*',
                'i.nama as nama_barang',
                'i.kategori',
                'i.kondisi',
                'i.deskripsi'
            )
            ->where('p.id', $id)
            ->where('p.user_id', Auth::id())
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $peminjaman
        ]);
    }

    public function update(Request $request, $id)
    {
        $peminjaman = DB::table('peminjaman')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        if ($peminjaman->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak dapat dibatalkan'
            ], 400);
        }

        $action = $request->input('action');

        if ($action === 'cancel') {
            DB::table('peminjaman')
                ->where('id', $id)
                ->update([
                    'status' => 'cancelled',
                    'updated_at' => now(),
                ]);

            try {
                DB::table('activity_logs')->insert([
                    'user_id' => Auth::id(),
                    'action' => 'cancel_borrow_request',
                    'description' => 'Membatalkan peminjaman ID: ' . $id,
                    'created_at' => now(),
                ]);
            } catch (\Exception $logError) {
                Log::warning('Failed to insert activity log:', ['error' => $logError->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil dibatalkan'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Aksi tidak valid'
        ], 400);
    }

    public function history()
    {
        $history = DB::table('peminjaman as p')
            ->join('barang as i', 'p.barang_id', '=', 'i.id')
            ->select(
                'p.*',
                'i.nama as nama_barang',
                'i.kategori'
            )
            ->where('p.user_id', Auth::id())
            ->orderBy('p.created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    public function active()
    {
        $activeBorrows = DB::table('peminjaman as p')
            ->join('barang as i', 'p.barang_id', '=', 'i.id')
            ->select(
                'p.*',
                'i.nama as nama_barang',
                'i.kategori',
                'i.kondisi'
            )
            ->where('p.user_id', Auth::id())
            ->whereIn('p.status', ['approved', 'borrowed'])
            ->orderBy('p.tanggal_kembali', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $activeBorrows
        ]);
    }

    public function extend(Request $request, $id)
    {
        $validated = $request->validate([
            'new_end_date' => 'required|date|after:today',
            'reason' => 'required|string|max:255'
        ]);

        $peminjaman = DB::table('peminjaman')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'borrowed')
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak ditemukan atau tidak dapat diperpanjang'
            ], 404);
        }

        $extendedCount = $peminjaman->extended_count ?? 0;
        if ($extendedCount >= 2) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman sudah diperpanjang maksimal 2 kali'
            ], 400);
        }

        DB::table('peminjaman')
            ->where('id', $id)
            ->update([
                'tanggal_kembali' => $validated['new_end_date'],
                'extension_reason' => $validated['reason'],
                'extended_count' => DB::raw('COALESCE(extended_count, 0) + 1'),
                'extended_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil diperpanjang'
        ]);
    }
}
