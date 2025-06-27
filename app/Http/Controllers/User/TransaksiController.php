<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = DB::table('peminjaman')
            ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->select(
                'barang.kode as kode_barang',
                'barang.nama as nama_barang',
                'peminjaman.id',
                'users.name as peminjam',
                'peminjaman.tanggal_pinjam',
                'peminjaman.tanggal_kembali',
                'peminjaman.status'
            )
            ->orderBy('peminjaman.created_at', 'desc') // Tambahkan sorting
            ->paginate(10); // Ganti get() dengan paginate()

        return view('admin.transaksi', compact('transaksis'));
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
            // 1. Cek stok tersedia
            $barang = DB::table('barang')
                ->where('id', $validated['barang_id'])
                ->first();

            if (!$barang) {
                throw new \Exception('Barang tidak ditemukan');
            }

            if ($barang->jumlah < $validated['jumlah']) {
                throw new \Exception('Stok barang tidak mencukupi');
            }

            // 2. Kurangi stok barang
            DB::table('barang')
                ->where('id', $validated['barang_id'])
                ->decrement('jumlah', $validated['jumlah']);

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

            // Logika perubahan status
            if ($validated['status'] == 'dipinjam' && $transaksi->status == 'pending') {
                // Stok sudah dikurangi saat pengajuan (di method store)
                // Tidak perlu kurangi lagi
            }
            elseif ($validated['status'] == 'dikembalikan' && $transaksi->status != 'dikembalikan') {
                // Kembalikan stok
                DB::table('barang')
                    ->where('id', $transaksi->barang_id)
                    ->increment('jumlah', $transaksi->jumlah);
            }
            elseif ($transaksi->status == 'dikembalikan' && $validated['status'] != 'dikembalikan') {
                // Kurangi stok lagi jika status diubah dari dikembalikan
                DB::table('barang')
                    ->where('id', $transaksi->barang_id)
                    ->decrement('jumlah', $transaksi->jumlah);
            }

            // Update data transaksi
            $updateData = [
                'status' => $validated['status'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali' => $validated['tanggal_kembali'],
                'catatan' => $validated['catatan'] ?? null,
                'updated_at' => now(),
            ];

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

            // Jika peminjaman masih 'pending' atau 'dipinjam'
            if (in_array($transaksi->status, ['pending', 'dipinjam'])) {
                // Kembalikan stok barang
                DB::table('barang')
                    ->where('id', $transaksi->barang_id)
                    ->increment('jumlah', $transaksi->jumlah);
            }

            // Hapus transaksi
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