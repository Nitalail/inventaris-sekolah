<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardUserController extends Controller
{
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

        // Query barang tersedia
        $query = Barang::with(['kategori', 'ruangan'])
            ->whereIn('kondisi', ['baik', 'rusak_ringan'])
            ->where('jumlah', '>', 0);

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $barangTersedia = $query->get();

        return view('user.dashboard-user', [
            'barangTersedia' => $barangTersedia,
            'search' => $request->search ?? '',
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

            $barang = Barang::where('id', $barangId)
                ->whereIn('kondisi', ['baik', 'rusak_ringan'])
                ->where('jumlah', '>', 0)
                ->firstOrFail();

            // Cek stok cukup
            if ($barang->jumlah < $request->quantity) {
                return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $barang->jumlah);
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

            // Kurangi stok jika status langsung dipinjam
            if ($peminjaman->status == 'dipinjam') {
                $barang->decrement('jumlah', $request->quantity);
            }

            return back()->with('success', 'Permintaan peminjaman berhasil diajukan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
