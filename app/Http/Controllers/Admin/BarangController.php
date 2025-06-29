<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\SubBarang;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['kategori_id', 'ruangan_id']);
        $perPage = $request->input('perPage', 10);

        $barangs = Barang::with(['kategori', 'ruangan'])
            ->withCount('subBarang as sub_barang_count')
            ->when($filters['kategori_id'] ?? null, fn($q, $kategori_id) => $q->where('kategori_id', $kategori_id))
            ->when($filters['ruangan_id'] ?? null, fn($q, $ruangan_id) => $q->where('ruangan_id', $ruangan_id))
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);

        $subBarangs = SubBarang::with(['barang'])
            ->whereIn('barang_id', $barangs->pluck('id'))
            ->get();

        $kategori = Kategori::all();
        $ruangan = Ruangan::all();

        return view('admin.barang', compact('barangs', 'kategori', 'ruangan', 'filters', 'perPage', 'subBarangs'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $ruangan = Ruangan::all();

        return view('admin.barang.create', compact('kategori', 'ruangan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:barang,kode|max:50',
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'ruangan_id' => 'required|exists:ruangan,id',
            'sumber_dana' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Barang::create($validated);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function show($id)
    {
        $barang = Barang::with(['kategori', 'ruangan'])->findOrFail($id);

        return view('admin.barang.show', compact('barang'));
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategori = Kategori::all();
        $ruangan = Ruangan::all();

        return view('admin.barang.edit', compact('barang', 'kategori', 'ruangan'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode' => 'required|string',
            'nama' => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan' => 'required|string|max:50',
            'ruangan_id' => 'required|exists:ruangan,id',
            'sumber_dana' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        $barang->update($validated);

        return redirect()->back()->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus!');
    }

    public function export(Request $request)
    {
        // Placeholder fitur export, bisa dikembangkan lebih lanjut
        return redirect()->route('admin.barang.index')->with('info', 'Fitur ekspor sedang dalam pengembangan.');
    }

    public function print(Request $request)
    {
        $filters = $request->only(['kategori_id', 'ruangan_id', 'status', 'tahun']);

        $barangs = Barang::with(['kategori', 'ruangan'])
            ->when($filters['kategori_id'] ?? null, fn($q, $kategori_id) => $q->where('kategori_id', $kategori_id))
            ->when($filters['ruangan_id'] ?? null, fn($q, $ruangan_id) => $q->where('ruangan_id', $ruangan_id))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('kondisi', $status))
            ->when($filters['tahun'] ?? null, fn($q, $tahun) => $q->where('tahun_perolehan', $tahun))
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.barang.print', compact('barangs'));
    }
}
