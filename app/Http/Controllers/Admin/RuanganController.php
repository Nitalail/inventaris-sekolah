<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RuanganController extends Controller
{
    // Tampilkan daftar ruangan dengan pagination
    public function index()
    {
        // Load ruangan dengan menghitung jumlah sub barang
        $rooms = Ruangan::with(['barangs.subBarang'])
            ->paginate(10);
        
        // Hitung jumlah sub barang untuk setiap ruangan
        $rooms->getCollection()->transform(function ($room) {
            $room->total_sub_barang = $room->barangs->sum(function ($barang) {
                return $barang->subBarang->count();
            });
            return $room;
        });
        
        return view('admin.ruangan', compact('rooms'));
    }

    // Simpan data ruangan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_ruangan' => 'required|string|max:10|unique:ruangan,kode_ruangan',
            'nama_ruangan' => 'required|string|max:255',
            'status' => 'required|in:aktif,perbaikan,tidak_aktif',
            'deskripsi' => 'nullable|string',
        ]);

        Ruangan::create($validated);

        return redirect()->back()->with('success', 'Ruangan berhasil ditambahkan!');
    }

    // Update ruangan
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_ruangan' => 'required|string|max:10|unique:ruangan,kode_ruangan,' . $id,
            'nama_ruangan' => 'required|string|max:255',
            'status' => 'required|in:aktif,perbaikan,tidak_aktif',
            'deskripsi' => 'nullable|string',
        ]);

        $room = Ruangan::findOrFail($id);
        $room->update($validated);

        return redirect()->back()->with('success', 'Data ruangan berhasil diperbarui.');
    }

    // Hapus ruangan
    public function destroy($id)
    {
        $room = Ruangan::findOrFail($id);
        
        // Cek apakah ruangan sedang digunakan oleh barang
        if ($room->barangs()->count() > 0) {
            return redirect()->back()->with('error', 'Ruangan tidak dapat dihapus karena masih terdapat barang di ruangan ini.');
        }

        $room->delete();

        return redirect()->back()->with('success', 'Ruangan berhasil dihapus!');
    }

    // Export PDF
    public function exportPDF()
    {
        $rooms = Ruangan::with(['barangs.subBarang'])->get();
        
        // Hitung jumlah sub barang untuk setiap ruangan
        $rooms->transform(function ($room) {
            $room->total_sub_barang = $room->barangs->sum(function ($barang) {
                return $barang->subBarang->count();
            });
            return $room;
        });
        
        $pdf = Pdf::loadView('admin.export-ruangan-pdf', compact('rooms'));
        return $pdf->download('data-ruangan.pdf');
    }
}
