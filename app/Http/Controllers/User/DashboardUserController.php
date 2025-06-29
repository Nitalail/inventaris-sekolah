<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\AvailableStockTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardUserController extends Controller
{
    use AvailableStockTrait;
    public function index(Request $request)
    {
        $user = Auth::user();

        // Hitung statistik peminjaman
        $stats = [
            'activeLoans' => Peminjaman::where('user_id', $user->id)
                ->whereIn('status', ['dipinjam', 'dikonfirmasi'])
                ->count(),

            'totalHistory' => Peminjaman::where('user_id', $user->id)->count(),

            'completedLoans' => $this->calculateCompletedOnTimeLoans($user->id),

            'overdueLoans' => $this->calculateOverdueLoans($user->id),
        ];

        // Query barang tersedia - using trait for consistent stock counting
        $query = $this->barangWithAvailableStock()
            ->with(['kategori', 'ruangan'])
            ->having('available_sub_barang_count', '>', 0);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kode', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        $barangTersedia = $query->get();

        // Ambil semua kategori untuk dropdown
        $kategoris = Kategori::orderBy('nama')->get();

        return view('user.dashboard-user', [
            'barangTersedia' => $barangTersedia,
            'kategoris' => $kategoris,
            'search' => $request->search ?? '',
            'selectedKategori' => $request->kategori ?? '',
            'stats' => $stats,
        ]);
    }

    protected function calculateCompletedOnTimeLoans($userId)
    {
        return DB::table('peminjaman')
            ->where('user_id', $userId)
            ->where('status', 'dikembalikan') // Menggunakan status 'dikembalikan' sesuai RiwayatController
            ->where(function ($query) {
                // Jika ada kolom tanggal_kembali_rencana, bandingkan dengan tanggal_kembali
                if (Schema::hasColumn('peminjaman', 'tanggal_kembali_rencana') && Schema::hasColumn('peminjaman', 'tanggal_kembali')) {
                    $query->whereRaw('tanggal_kembali <= tanggal_kembali_rencana');
                }
                // Jika hanya ada tanggal_kembali, anggap semua yang selesai tepat waktu
                elseif (Schema::hasColumn('peminjaman', 'tanggal_kembali')) {
                    $query->whereNotNull('tanggal_kembali');
                }
            })
            ->count();
    }

    protected function calculateOverdueLoans($userId)
    {
        $query = Peminjaman::where('user_id', $userId)->where('status', 'dipinjam');

        // Cek kolom yang tersedia di database
        if (Schema::hasColumn('peminjaman', 'tanggal_kembali_rencana')) {
            return $query->where('tanggal_kembali_rencana', '<', Carbon::now())->count();
        } elseif (Schema::hasColumn('peminjaman', 'tanggal_kembali')) {
            return $query->where('tanggal_kembali', '<', Carbon::now())->count();
        }

        return 0;
    }

    public function pinjamBarang(Request $request, $barangId)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1',
                'tanggal_kembali_rencana' => 'required|date|after_or_equal:today',
            ]);

            // Get barang with available sub barang count using trait
            $barang = $this->barangWithAvailableStock()->findOrFail($barangId);

            // Cek stok sub barang tersedia
            if ($barang->available_sub_barang_count < $request->quantity) {
                return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $barang->available_sub_barang_count);
            }

            // Cek apakah user sudah meminjam barang yang sama dan belum dikembalikan
            $existingLoan = Peminjaman::where('user_id', auth()->id())
                ->where('barang_id', $barangId)
                ->whereIn('status', ['dipinjam', 'pending'])
                ->exists();

            if ($existingLoan) {
                return back()->with('error', 'Anda sudah meminjam barang ini sebelumnya!');
            }

            // Buat peminjaman
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'barang_id' => $barangId,
                'quantity' => $request->quantity,
                'tanggal_pinjam' => now(),
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'keperluan' => $request->keperluan,
                'status' => 'pending', // Atau 'dipinjam' tergantung workflow
            ]);

            // Note: Sub barang assignment logic can be implemented here later
            // For now, the peminjaman is created but specific sub barang items
            // are not yet assigned. This can be handled in the admin approval process.

            return back()->with('success', 'Permintaan peminjaman berhasil diajukan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
