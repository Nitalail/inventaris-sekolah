<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PinjamanSayaController extends Controller
{
    public function index()
    {
        // Ambil data peminjaman user yang sedang berlangsung (bukan status dikembalikan)
        $peminjaman = DB::table('peminjaman')
            ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->join('kategoris', 'barang.kategori_id', '=', 'kategoris.id')
            ->select(
                'peminjaman.id',
                'peminjaman.tanggal_pinjam',
                'peminjaman.tanggal_kembali',
                'peminjaman.jumlah',
                'peminjaman.keperluan',
                'peminjaman.catatan',
                'peminjaman.status',
                'peminjaman.created_at',
                'peminjaman.updated_at',
                'barang.nama as nama_barang',
                'barang.kode as kode_barang',
                'kategoris.nama as kategori',
                'users.name as nama_peminjam'
            )
            ->where('peminjaman.user_id', auth()->id())
            ->whereIn('peminjaman.status', ['pending', 'dipinjam', 'terlambat', 'rusak'])
            ->orderBy('peminjaman.created_at', 'desc')
            ->get();

        // Format data peminjaman untuk tampilan
        $peminjamanData = $peminjaman->map(function ($item) {
            $tanggalPinjam = Carbon::parse($item->tanggal_pinjam);
            $tanggalKembali = Carbon::parse($item->tanggal_kembali);
            $sekarang = Carbon::now();
            
            // Hitung sisa hari atau hari terlambat
            $sisaHari = $sekarang->diffInDays($tanggalKembali, false);
            $statusDisplay = $this->getStatusDisplay($item->status, $sisaHari, $tanggalKembali);
            
            return [
                'id' => $item->id,
                'itemName' => $item->nama_barang,
                'itemCode' => $item->kode_barang,
                'category' => $this->getCategoryKey($item->kategori),
                'categoryName' => $item->kategori,
                'borrowDate' => $item->tanggal_pinjam,
                'returnDate' => $item->tanggal_kembali,
                'quantity' => $item->jumlah,
                'purpose' => $item->keperluan,
                'notes' => $item->catatan,
                'status' => $item->status,
                'statusDisplay' => $statusDisplay,
                'remainingDays' => $sisaHari,
                'isOverdue' => $sisaHari < 0,
                'isDueSoon' => $sisaHari >= 0 && $sisaHari <= 3,
                'canExtend' => in_array($item->status, ['dipinjam']) && $sisaHari >= 0,
                'createdAt' => $item->created_at
            ];
        });

        // Hitung statistik
        $stats = [
            'total' => $peminjamanData->count(),
            'pending' => $peminjamanData->where('status', 'pending')->count(),
            'active' => $peminjamanData->where('status', 'dipinjam')->count(),
            'overdue' => $peminjamanData->where('status', 'terlambat')->count(),
            'damaged' => $peminjamanData->where('status', 'rusak')->count(),
            'dueSoon' => $peminjamanData->where('isDueSoon', true)->count()
        ];

        return view('user.pinjaman-saya', compact('peminjamanData', 'stats'));
    }

    // Method untuk mendapatkan key kategori
    private function getCategoryKey($categoryName)
    {
        $categoryMapping = [
            'Buku' => 'buku',
            'Alat Tulis' => 'alat-tulis', 
            'Elektronik' => 'elektronik',
            'Olahraga' => 'olahraga'
        ];

        return $categoryMapping[$categoryName] ?? strtolower(str_replace(' ', '-', $categoryName));
    }

    // Method untuk mendapatkan display status
    private function getStatusDisplay($status, $sisaHari, $tanggalKembali)
    {
        switch ($status) {
            case 'pending':
                return [
                    'text' => 'Menunggu Persetujuan',
                    'class' => 'bg-yellow-100 text-yellow-700',
                    'icon' => '⏳'
                ];
            case 'dipinjam':
                if ($sisaHari < 0) {
                    return [
                        'text' => 'Terlambat',
                        'class' => 'bg-red-100 text-red-700 animate-pulse',
                        'icon' => '❌'
                    ];
                } elseif ($sisaHari <= 3) {
                    return [
                        'text' => 'Segera Berakhir',
                        'class' => 'bg-orange-100 text-orange-700',
                        'icon' => '⏰'
                    ];
                } else {
                    return [
                        'text' => 'Aktif',
                        'class' => 'bg-green-100 text-green-700',
                        'icon' => '✅'
                    ];
                }
            case 'terlambat':
                return [
                    'text' => 'Terlambat',
                    'class' => 'bg-red-100 text-red-700 animate-pulse',
                    'icon' => '❌'
                ];
            case 'rusak':
                return [
                    'text' => 'Rusak',
                    'class' => 'bg-gray-100 text-gray-700',
                    'icon' => '🔧'
                ];
            default:
                return [
                    'text' => 'Tidak Diketahui',
                    'class' => 'bg-gray-100 text-gray-700',
                    'icon' => '❓'
                ];
        }
    }

    // Method untuk request perpanjangan
    public function requestExtension(Request $request, $id)
    {
        try {
            $peminjaman = DB::table('peminjaman')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', 'dipinjam')
                ->first();

            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman tidak ditemukan atau tidak dapat diperpanjang'
                ], 404);
            }

            // Cek apakah sudah terlambat
            $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
            if (Carbon::now()->gt($tanggalKembali)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat memperpanjang peminjaman yang sudah terlambat'
                ], 400);
            }

            // Update status atau catat request perpanjangan
            // Bisa dibuat tabel terpisah untuk tracking perpanjangan
            DB::table('peminjaman')->where('id', $id)->update([
                'catatan' => ($peminjaman->catatan ?? '') . ' [REQUEST PERPANJANGAN: ' . now() . ']',
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan perpanjangan telah dikirim. Menunggu persetujuan pustakawan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk filter berdasarkan status
    public function filterByStatus(Request $request)
    {
        $status = $request->get('status');
        $category = $request->get('category');
        
        $query = DB::table('peminjaman')
            ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->join('kategoris', 'barang.kategori_id', '=', 'kategoris.id')
            ->select(
                'peminjaman.id',
                'peminjaman.tanggal_pinjam',
                'peminjaman.tanggal_kembali',
                'peminjaman.jumlah',
                'peminjaman.keperluan',
                'peminjaman.catatan',
                'peminjaman.status',
                'peminjaman.created_at',
                'barang.nama as nama_barang',
                'barang.kode as kode_barang',
                'kategoris.nama as kategori',
                'users.name as nama_peminjam'
            )
            ->where('peminjaman.user_id', auth()->id())
            ->whereIn('peminjaman.status', ['pending', 'dipinjam', 'terlambat', 'rusak']);

        // Filter berdasarkan status
        if ($status && $status !== 'all') {
            if ($status === 'due-soon') {
                // Logic untuk segera berakhir (3 hari atau kurang)
                $query->where('peminjaman.status', 'dipinjam')
                      ->whereRaw('DATEDIFF(peminjaman.tanggal_kembali, NOW()) <= 3')
                      ->whereRaw('DATEDIFF(peminjaman.tanggal_kembali, NOW()) >= 0');
            } elseif ($status === 'overdue') {
                $query->where(function($q) {
                    $q->where('peminjaman.status', 'terlambat')
                      ->orWhere(function($q2) {
                          $q2->where('peminjaman.status', 'dipinjam')
                             ->whereRaw('DATEDIFF(peminjaman.tanggal_kembali, NOW()) < 0');
                      });
                });
            } else {
                $query->where('peminjaman.status', $status);
            }
        }

        // Filter berdasarkan kategori
        if ($category && $category !== 'all') {
            $query->where('kategoris.nama', $category);
        }

        $peminjaman = $query->orderBy('peminjaman.created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $peminjaman
        ]);
    }

    // Method untuk update auto status terlambat
    public function updateOverdueStatus()
    {
        $overdueItems = DB::table('peminjaman')
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now()->toDateString())
            ->update([
                'status' => 'terlambat',
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => "{$overdueItems} item(s) diupdate menjadi terlambat"
        ]);
    }
}