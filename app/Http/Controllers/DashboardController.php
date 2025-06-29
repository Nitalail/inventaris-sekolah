<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\SubBarang;
use App\Models\Peminjaman;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Waktu cache dalam menit
    const CACHE_TIME_STATS = 30;
    const CACHE_TIME_TRANSACTIONS = 15;
    const CACHE_TIME_LOW_STOCK = 360; // 6 jam
    
    // Threshold untuk stok menipis
    const LOW_STOCK_THRESHOLD = 10;
    
    // Jumlah data yang ditampilkan
    const RECENT_TRANSACTIONS_LIMIT = 5;
    const LOW_STOCK_ITEMS_LIMIT = 5;

    public function index()
    {
        $stats = $this->getDashboardStats();
        $recentTransactions = $this->getRecentTransactions();
        $lowStockItems = $this->getLowStockItems();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentTransactions' => $recentTransactions,
            'lowStockItems' => $lowStockItems
        ]);
    }

    public function getData()
    {
        try {
            $stats = $this->getDashboardStats();
            $recentTransactions = $this->getRecentTransactions();
            $lowStockItems = $this->getLowStockItems();

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'recentTransactions' => $recentTransactions,
                'lowStockItems' => $lowStockItems
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading dashboard data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    protected function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', now()->addMinutes(self::CACHE_TIME_STATS), function () {
            $basicStats = $this->calculateBasicStats();
            $transactionStats = $this->calculateTransactionStats();
            return array_merge($basicStats, $transactionStats);
        });
    }

    protected function calculateBasicStats()
    {
        // Hitung total sub barang
        $totalSubBarang = SubBarang::count();
        
        // Hitung sub barang yang sedang dipinjam
        $borrowedSubBarang = DB::select("
            SELECT COUNT(DISTINCT sub_barang.id) as count
            FROM sub_barang 
            WHERE EXISTS (
                SELECT 1 FROM peminjaman 
                WHERE JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))
                AND peminjaman.status IN ('pending', 'dipinjam', 'dikonfirmasi')
            )
        ")[0]->count ?? 0;

        $totalKategori = Kategori::count();
        $totalRuangan = Ruangan::count();
        $lastMonth = Carbon::now()->subMonth();
        
        // Hitung perubahan sub barang
        $totalSubBarangLastMonth = SubBarang::where('created_at', '<=', $lastMonth)->count();
        
        return [
            'total_items' => $totalSubBarang,
            'borrowed_items' => $borrowedSubBarang,
            'items_change' => $this->calculatePercentageChange(
                $totalSubBarangLastMonth,
                $totalSubBarang
            ),
            'categories' => $totalKategori,
            'categories_change' => $this->calculatePercentageChange(
                Kategori::where('created_at', '<=', $lastMonth)->count(),
                $totalKategori
            ),
            'rooms' => $totalRuangan,
            'rooms_change' => $this->calculatePercentageChange(
                Ruangan::where('created_at', '<=', $lastMonth)->count(),
                $totalRuangan
            ),
            'borrowed_change' => $this->calculateBorrowedChange($borrowedSubBarang, $lastMonth)
        ];
    }

    protected function calculateBorrowedChange($currentBorrowed, $lastMonth)
    {
        // Hitung jumlah sub barang yang dipinjam bulan lalu
        $borrowedLastMonth = DB::select("
            SELECT COUNT(DISTINCT sub_barang.id) as count
            FROM sub_barang 
            WHERE EXISTS (
                SELECT 1 FROM peminjaman 
                WHERE JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))
                AND peminjaman.status IN ('pending', 'dipinjam', 'dikonfirmasi')
                AND peminjaman.created_at <= ?
            )
        ", [$lastMonth])[0]->count ?? 0;

        return $this->calculatePercentageChange($borrowedLastMonth, $currentBorrowed);
    }

    protected function calculateTransactionStats()
    {
        $today = Carbon::today();
        
        return [
            'total_transactions' => Peminjaman::count(),
            'active_loans' => Peminjaman::whereIn('status', ['dipinjam', 'dikonfirmasi'])->count(),
            'pending_loans' => Peminjaman::where('status', 'pending')->count(),
            'today_transactions' => Peminjaman::whereDate('created_at', $today)->count(),
            'completed_loans' => Peminjaman::where('status', 'dikembalikan')->count()
        ];
    }

    protected function getRecentTransactions()
    {
        return Cache::remember('recent_transactions', now()->addMinutes(self::CACHE_TIME_TRANSACTIONS), function () {
            $transactions = Peminjaman::with(['barang', 'user'])
                ->latest()
                ->limit(self::RECENT_TRANSACTIONS_LIMIT)
                ->get()
                ->map(function ($peminjaman, $index) {
                    // Hitung jumlah sub barang yang dipinjam
                    $subBarangCount = 0;
                    if ($peminjaman->sub_barang_ids) {
                        // sub_barang_ids sudah di-cast sebagai array di model
                        $subBarangIds = is_array($peminjaman->sub_barang_ids) 
                            ? $peminjaman->sub_barang_ids 
                            : json_decode($peminjaman->sub_barang_ids, true);
                        $subBarangCount = is_array($subBarangIds) ? count($subBarangIds) : 0;
                    }

                    return [
                        'id' => $index + 1, // ID berurutan mulai dari 1
                        'original_id' => $peminjaman->id, // ID asli untuk referensi
                        'item_name' => $peminjaman->barang->nama ?? 'Barang tidak ditemukan',
                        'peminjam' => $peminjaman->user->name ?? 'User tidak ditemukan',
                        'quantity' => $subBarangCount,
                        'date' => $peminjaman->created_at->translatedFormat('d M Y'),
                        'status' => $this->normalizeStatus($peminjaman->status),
                        'tanggal_kembali' => $peminjaman->tanggal_kembali 
                            ? Carbon::parse($peminjaman->tanggal_kembali)->translatedFormat('d M Y') 
                            : '-',
                        'is_late' => $peminjaman->tanggal_kembali 
                            ? Carbon::now()->gt(Carbon::parse($peminjaman->tanggal_kembali))
                            : false
                    ];
                })
                ->toArray();
            
            return $transactions;
        });
    }

    protected function getLowStockItems()
    {
        return Cache::remember('low_stock_items', now()->addMinutes(self::CACHE_TIME_LOW_STOCK), function () {
            return Barang::with(['kategori', 'ruangan'])
                ->withCount(['subBarang as available_stock' => function ($query) {
                    $query->whereIn('kondisi', ['baik', 'rusak_ringan'])
                          ->whereNotExists(function ($subQuery) {
                              $subQuery->select(\DB::raw(1))
                                       ->from('peminjaman')
                                       ->whereRaw('JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))')
                                       ->whereIn('peminjaman.status', ['pending', 'dipinjam', 'dikonfirmasi']);
                          });
                }])
                ->having('available_stock', '<=', self::LOW_STOCK_THRESHOLD)
                ->orderBy('available_stock')
                ->limit(self::LOW_STOCK_ITEMS_LIMIT)
                ->get()
                ->map(function ($barang) {
                    return [
                        'id' => $barang->id,
                        'code' => $barang->kode ?? $barang->kode_barang,
                        'name' => $barang->nama ?? $barang->nama_barang,
                        'category' => $barang->kategori->nama ?? 'Tidak ada kategori',
                        'room' => $barang->ruangan->nama_ruangan ?? 'Tidak ada ruangan',
                        'stock' => $barang->available_stock,
                        'min_stock' => 5, // Default minimum stock
                        'is_critical' => $barang->available_stock <= 3 // Critical if 3 or less
                    ];
                })
                ->toArray();
        });
    }

    protected function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue == 0 ? 0 : 100;
        }
        
        return round(($newValue - $oldValue) / $oldValue * 100, 2);
    }

    protected function normalizeStatus($status)
    {
        $statusMap = [
            'pending' => 'Menunggu Persetujuan',
            'dipinjam' => 'Dipinjam',
            'dikonfirmasi' => 'Dikonfirmasi',
            'dikembalikan' => 'Dikembalikan',
            'terlambat' => 'Terlambat',
            'rusak' => 'Rusak'
        ];
        
        return $statusMap[$status] ?? ucfirst($status);
    }
}