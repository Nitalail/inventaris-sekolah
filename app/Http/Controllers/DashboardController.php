<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\SubBarang;
use App\Models\Peminjaman;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
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
        $mostBorrowedItems = $this->getMostBorrowedItems();
        $stockReport = $this->getStockReport();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentTransactions' => $recentTransactions,
            'lowStockItems' => $lowStockItems,
            'mostBorrowedItems' => $mostBorrowedItems,
            'stockReport' => $stockReport
        ]);
    }

    public function getData()
    {
        try {
            $stats = $this->getDashboardStats();
            $recentTransactions = $this->getRecentTransactions();
            $lowStockItems = $this->getLowStockItems();
            $mostBorrowedItems = $this->getMostBorrowedItems();
            $stockReport = $this->getStockReport();

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'recentTransactions' => $recentTransactions,
                'lowStockItems' => $lowStockItems,
                'mostBorrowedItems' => $mostBorrowedItems,
                'stockReport' => $stockReport
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function getDashboardStats()
    {
        $basicStats = $this->calculateBasicStats();
        $transactionStats = $this->calculateTransactionStats();
        return array_merge($basicStats, $transactionStats);
    }

    protected function calculateBasicStats()
    {
        // Hitung total sub barang (hanya yang aktif, tidak termasuk nonaktif)
        $totalSubBarang = SubBarang::where('kondisi', '!=', 'nonaktif')->count();
        
        // Hitung sub barang yang sedang dipinjam (hanya yang aktif)
        $borrowedSubBarang = DB::select("
            SELECT COUNT(DISTINCT sub_barang.id) as count
            FROM sub_barang 
            WHERE sub_barang.kondisi != 'nonaktif'
            AND EXISTS (
                SELECT 1 FROM peminjaman 
                WHERE JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))
                AND peminjaman.status IN ('pending', 'dipinjam', 'dikonfirmasi')
            )
        ")[0]->count ?? 0;

        $totalKategori = Kategori::count();
        $totalRuangan = Ruangan::count();
        $lastMonth = Carbon::now()->subMonth();
        
        // Hitung perubahan sub barang (hanya yang aktif)
        $totalSubBarangLastMonth = SubBarang::where('kondisi', '!=', 'nonaktif')
            ->where('created_at', '<=', $lastMonth)
            ->count();
        
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
        // Hitung jumlah sub barang yang dipinjam bulan lalu (hanya yang aktif)
        $borrowedLastMonth = DB::select("
            SELECT COUNT(DISTINCT sub_barang.id) as count
            FROM sub_barang 
            WHERE sub_barang.kondisi != 'nonaktif'
            AND EXISTS (
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
    }

    protected function getLowStockItems()
    {
        return Barang::with(['kategori', 'ruangan'])
            ->withCount(['subBarang as available_stock' => function ($query) {
                $query->whereIn('kondisi', ['baik', 'rusak_ringan'])
                      ->where('bisa_dipinjam', true);
            }])
            ->having('available_stock', '<=', self::LOW_STOCK_THRESHOLD)
            ->having('available_stock', '>', 0) // Exclude items with 0 stock (they're in out of stock)
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
                    'available_stock' => $barang->available_stock,
                    'min_stock' => 5, // Default minimum stock
                    'is_critical' => $barang->available_stock <= 3 // Critical if 3 or less
                ];
            })
            ->toArray();
    }

    protected function getMostBorrowedItems()
    {
        try {
            return DB::table('peminjaman')
                ->select('barang.nama as item_name', DB::raw('COUNT(DISTINCT peminjaman.id) as borrow_count'))
                ->join('sub_barang', function($join) {
                    $join->whereRaw('JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))');
                })
                ->join('barang', 'sub_barang.barang_id', '=', 'barang.id')
                ->whereIn('peminjaman.status', ['dipinjam', 'dikonfirmasi', 'dikembalikan'])
                ->whereNotNull('peminjaman.sub_barang_ids')
                ->where('peminjaman.sub_barang_ids', '!=', '[]')
                ->groupBy('barang.id', 'barang.nama')
                ->orderByDesc('borrow_count')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'item_name' => $item->item_name ?? 'Barang tidak ditemukan',
                        'borrow_count' => (int) $item->borrow_count
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            \Log::error('Error getting most borrowed items: ' . $e->getMessage());
            return [];
        }
    }

    protected function getStockReport()
    {
        try {
            // Get low stock items (reuse existing method)
            $lowStockItems = $this->getLowStockItems();
            
            // Get out of stock items - items with no available sub-barang
            $outOfStockItems = Barang::with(['kategori', 'ruangan'])
                ->withCount(['subBarang as available_stock' => function ($query) {
                    $query->whereIn('kondisi', ['baik', 'rusak_ringan'])
                          ->where('bisa_dipinjam', true);
                }])
                ->having('available_stock', '=', 0)
                ->get()
                ->map(function ($barang) {
                    return [
                        'id' => $barang->id,
                        'code' => $barang->kode ?? $barang->kode_barang,
                        'name' => $barang->nama ?? $barang->nama_barang,
                        'category' => $barang->kategori->nama ?? 'Tidak ada kategori',
                        'room' => $barang->ruangan->nama_ruangan ?? 'Tidak ada ruangan',
                        'available_stock' => $barang->available_stock,
                        'is_critical' => true // Always critical for out of stock
                    ];
                })
                ->toArray();

            return [
                'low_stock_items' => $lowStockItems,
                'out_of_stock_items' => $outOfStockItems
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting stock report: ' . $e->getMessage());
            return [
                'low_stock_items' => [],
                'out_of_stock_items' => []
            ];
        }
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