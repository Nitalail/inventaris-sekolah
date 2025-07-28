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
            ->withCount(['subBarang as sub_barang_count' => function ($query) {
                $query->where('kondisi', '!=', 'nonaktif');
            }])
            ->when($filters['kategori_id'] ?? null, fn($q, $kategori_id) => $q->where('kategori_id', $kategori_id))
            ->when($filters['ruangan_id'] ?? null, fn($q, $ruangan_id) => $q->where('ruangan_id', $ruangan_id))
            // Tampilkan semua barang (tidak ada lagi filter status)
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);

        $subBarangs = SubBarang::with(['barang'])
            ->whereIn('barang_id', $barangs->pluck('id'))
            ->get();

        $kategori = Kategori::all();
        // Hanya tampilkan ruangan yang aktif (tidak sedang diperbaiki atau tidak aktif)
        $ruangan = Ruangan::active()->get();

        return view('admin.barang', compact('barangs', 'kategori', 'ruangan', 'filters', 'perPage', 'subBarangs'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        // Hanya tampilkan ruangan yang aktif untuk pembuatan barang baru
        $ruangan = Ruangan::active()->get();

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
        // Hanya tampilkan ruangan yang aktif untuk edit barang
        $ruangan = Ruangan::active()->get();

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

    public function export(Request $request)
    {
        // Placeholder fitur export, bisa dikembangkan lebih lanjut
        return redirect()->route('admin.barang.index')->with('info', 'Fitur ekspor sedang dalam pengembangan.');
    }

    public function print(Request $request)
    {
        // Placeholder fitur print, bisa dikembangkan lebih lanjut
        return redirect()->route('admin.barang.index')->with('info', 'Fitur print sedang dalam pengembangan.');
    }

    // Get count for auto-generation
    public function getCount()
    {
        $count = Barang::count();
        return response()->json(['count' => $count]);
    }
}
