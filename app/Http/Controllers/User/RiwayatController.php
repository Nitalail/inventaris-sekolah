<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melihat riwayat.');
        }

        // Ambil data riwayat peminjaman user (hanya status dikembalikan)
        $riwayat = DB::table('peminjaman')
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
            ->where('peminjaman.user_id', Auth::id())
            ->where('peminjaman.status', 'dikembalikan') // HANYA TAMPILKAN STATUS DIKEMBALIKAN
            ->orderBy('peminjaman.updated_at', 'desc') // Urutkan berdasarkan tanggal pengembalian terbaru
            ->get();

        // Format data riwayat untuk tampilan
        $riwayatData = $riwayat->map(function ($item) {
            $tanggalPinjam = Carbon::parse($item->tanggal_pinjam);
            $tanggalKembali = Carbon::parse($item->tanggal_kembali);
            $tanggalUpdate = Carbon::parse($item->updated_at); // Tanggal actual pengembalian
            $durasi = $tanggalPinjam->diffInDays($tanggalKembali);
            
            // Cek apakah dikembalikan tepat waktu atau terlambat
            $isOnTime = $tanggalUpdate->lte($tanggalKembali);
            $keterlambatan = $isOnTime ? 0 : $tanggalUpdate->diffInDays($tanggalKembali);

            return [
                'id' => $item->id,
                'itemName' => $item->nama_barang,
                'itemCode' => $item->kode_barang,
                'category' => $this->getCategoryKey($item->kategori),
                'categoryName' => $item->kategori,
                'borrowDate' => $item->tanggal_pinjam,
                'returnDate' => $item->tanggal_kembali,
                'actualReturnDate' => $item->updated_at, // Tanggal actual pengembalian
                'quantity' => $item->jumlah,
                'purpose' => $item->keperluan,
                'notes' => $item->catatan,
                'duration' => $durasi,
                'isOnTime' => $isOnTime,
                'lateDays' => $keterlambatan,
                'status' => 'completed' // Semua sudah selesai
            ];
        });

        // Hitung statistik riwayat
        $stats = [
            'total' => $riwayatData->count(),
            'onTime' => $riwayatData->where('isOnTime', true)->count(),
            'late' => $riwayatData->where('isOnTime', false)->count(),
            'thisMonth' => $riwayatData->filter(function ($item) {
                return Carbon::parse($item['actualReturnDate'])->isCurrentMonth();
            })->count()
        ];

        // Kirim data ke view
        return view('user.riwayat', compact('riwayatData', 'stats'));
    }

    // METHOD UNTUK MENDAPATKAN KEY KATEGORI
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

    // METHOD UNTUK EXPORT PDF (OPSIONAL)
    public function exportPdf()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengekspor data.');
        }

        // Ambil data riwayat yang sama
        $riwayat = DB::table('peminjaman')
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
                'peminjaman.updated_at',
                'barang.nama as nama_barang',
                'barang.kode as kode_barang',
                'kategoris.nama as kategori',
                'users.name as nama_peminjam'
            )
            ->where('peminjaman.user_id', Auth::id())
            ->where('peminjaman.status', 'dikembalikan')
            ->orderBy('peminjaman.updated_at', 'desc')
            ->get();

        // Di sini Anda bisa menggunakan library seperti DomPDF atau mPDF
        // Untuk sekarang, kita return JSON sebagai contoh
        return response()->json([
            'success' => true,
            'message' => 'Data riwayat berhasil diekspor',
            'data' => $riwayat
        ]);
    }

    // METHOD UNTUK FILTER BERDASARKAN KATEGORI
    public function filterByCategory(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
        }

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
                'peminjaman.updated_at',
                'barang.nama as nama_barang',
                'barang.kode as kode_barang',
                'kategoris.nama as kategori',
                'users.name as nama_peminjam'
            )
            ->where('peminjaman.user_id', Auth::id())
            ->where('peminjaman.status', 'dikembalikan');

        if ($category && $category !== 'all') {
            $query->where('kategoris.nama', $category);
        }

        $riwayat = $query->orderBy('peminjaman.updated_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $riwayat
        ]);
    }

    // METHOD UNTUK FILTER BERDASARKAN BULAN
    public function filterByMonth(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
        }

        $month = $request->get('month'); // Format: YYYY-MM
        
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
                'peminjaman.updated_at',
                'barang.nama as nama_barang',
                'barang.kode as kode_barang',
                'kategoris.nama as kategori',
                'users.name as nama_peminjam'
            )
            ->where('peminjaman.user_id', Auth::id())
            ->where('peminjaman.status', 'dikembalikan');

        if ($month && $month !== 'all') {
            $query->whereRaw('DATE_FORMAT(peminjaman.updated_at, "%Y-%m") = ?', [$month]);
        }

        $riwayat = $query->orderBy('peminjaman.updated_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $riwayat
        ]);
    }

    // METHOD UNTUK PENCARIAN
    public function search(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
        }

        $searchTerm = $request->get('search');
        
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
                'peminjaman.updated_at',
                'barang.nama as nama_barang',
                'barang.kode as kode_barang',
                'kategoris.nama as kategori',
                'users.name as nama_peminjam'
            )
            ->where('peminjaman.user_id', Auth::id())
            ->where('peminjaman.status', 'dikembalikan');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('barang.nama', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('barang.kode', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('kategoris.nama', 'LIKE', "%{$searchTerm}%");
            });
        }

        $riwayat = $query->orderBy('peminjaman.updated_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $riwayat
        ]);
    }
}