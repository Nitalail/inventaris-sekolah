<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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

    protected function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', now()->addMinutes(self::CACHE_TIME_STATS), function () {
            $stats = $this->calculateBasicStats();
            return array_merge($stats, $this->calculateTransactionStats());
        });
    }

    protected function calculateBasicStats()
    {
        $totalBarang = Barang::count();
        $totalKategori = Kategori::count();
        $totalRuangan = Ruangan::count();
        $lastMonth = Carbon::now()->subMonth();
        
        return [
            'total_items' => $totalBarang,
            'borrowed_items' => Transaksi::where('status', 'Dipinjam')->count(),
            'items_change' => $this->calculatePercentageChange(
                Barang::where('created_at', '<=', $lastMonth)->count(),
                $totalBarang
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
            'borrowed_change' => $this->calculatePercentageChange(
                Transaksi::where('status', 'Dipinjam')
                    ->where('created_at', '<=', $lastMonth)
                    ->count(),
                Transaksi::where('status', 'Dipinjam')->count()
            )
        ];
    }

    protected function calculateTransactionStats()
    {
        $today = Carbon::today();
        
        return [
            'total_transactions' => Transaksi::count(),
            'active_loans' => Transaksi::where('status', 'Dipinjam')->count(),
            'overdue' => Transaksi::where('status', 'Terlambat')->count(),
            'today_transactions' => Transaksi::whereDate('created_at', $today)->count(),
            'pending_returns' => Transaksi::where('status', 'Menunggu Pengembalian')->count()
        ];
    }

    protected function getRecentTransactions()
    {
        return Cache::remember('recent_transactions', now()->addMinutes(self::CACHE_TIME_TRANSACTIONS), function () {
            return Transaksi::with(['barang', 'user'])
                ->latest()
                ->limit(self::RECENT_TRANSACTIONS_LIMIT)
                ->get()
                ->map(function ($transaksi) {
                    return [
                        'id' => $transaksi->id,
                        'item_name' => $transaksi->barang->nama_barang ?? 'Barang tidak ditemukan',
                        'peminjam' => $transaksi->user->name ?? $transaksi->peminjam,
                        'date' => $transaksi->created_at->translatedFormat('d M Y'),
                        'status' => $this->normalizeStatus($transaksi->status),
                        'tanggal_kembali' => $transaksi->tanggal_kembali 
                            ? Carbon::parse($transaksi->tanggal_kembali)->translatedFormat('d M Y') 
                            : '-',
                        'is_late' => $transaksi->tanggal_kembali 
                            ? Carbon::now()->gt(Carbon::parse($transaksi->tanggal_kembali))
                            : false
                    ];
                })
                ->toArray();
        });
    }

    protected function getLowStockItems()
    {
        return Cache::remember('low_stock_items', now()->addMinutes(self::CACHE_TIME_LOW_STOCK), function () {
            return Barang::with(['kategori', 'ruangan'])
                ->where('jumlah', '<=', self::LOW_STOCK_THRESHOLD)
                ->orderBy('jumlah')
                ->limit(self::LOW_STOCK_ITEMS_LIMIT)
                ->get()
                ->map(function ($barang) {
                    return [
                        'id' => $barang->id,
                        'code' => $barang->kode_barang,
                        'name' => $barang->nama_barang,
                        'category' => $barang->kategori->nama ?? 'Tidak ada kategori',
                        'room' => $barang->ruangan->nama_ruangan ?? 'Tidak ada ruangan',
                        'stock' => $barang->jumlah,
                        'min_stock' => $barang->stok_minimal ?? 5,
                        'is_critical' => $barang->jumlah <= ($barang->stok_minimal ?? 3)
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
            'Dipinjam' => 'Dipinjam',
            'Dikembalikan' => 'Dikembalikan',
            'Terlambat' => 'Terlambat',
            'Diperbaiki' => 'Diperbaiki',
            'Pending' => 'Menunggu Persetujuan',
            'WaitingReturn' => 'Menunggu Pengembalian'
        ];
        
        return $statusMap[$status] ?? $status;
    }
}