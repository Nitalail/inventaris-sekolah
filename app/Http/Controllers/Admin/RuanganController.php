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
        // Gunakan paginate() instead of all()
        $rooms = Ruangan::paginate(10); // 10 records per page
        return view('admin.ruangan', compact('rooms'));
    }

    // Simpan data ruangan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_ruangan' => 'required|string|max:10|unique:ruangan,kode_ruangan',
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'jumlah_barang' => 'required|integer|min:0',
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
            'kapasitas' => 'required|integer|min:1',
            'jumlah_barang' => 'required|integer|min:0',
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
        $room->delete();

        return redirect()->back()->with('success', 'Ruangan berhasil dihapus!');
    }

    // Export PDF
    public function exportPDF()
    {
        $rooms = Ruangan::all(); // Use all() for PDF export to get all records
        $pdf = Pdf::loadView('admin.export-ruangan-pdf', compact('rooms'));
        return $pdf->download('data-ruangan.pdf');
    }
}
