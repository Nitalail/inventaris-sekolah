<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Ruangan;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Query pencarian tidak boleh kosong'
            ]);
        }

        $results = [
            'barang' => [],
            'transaksi' => [],
            'pengguna' => [],
            'kategori' => [],
            'ruangan' => []
        ];

        // Search Barang
        $barangResults = Barang::with(['kategori', 'ruangan'])
            ->where(function($q) use ($query) {
                $q->where('nama', 'LIKE', "%{$query}%")
                  ->orWhere('kode', 'LIKE', "%{$query}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get();

        $results['barang'] = $barangResults->map(function($item) {
            return [
                'id' => $item->id,
                'type' => 'barang',
                'title' => $item->nama,
                'subtitle' => $item->kode,
                'description' => $item->kategori->nama . ' - ' . $item->ruangan->nama,
                'url' => route('admin.barang.show', $item->id),
                'icon' => 'fas fa-box'
            ];
        });

        // Search Transaksi
        $transaksiResults = DB::table('peminjaman')
            ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->select('peminjaman.*', 'barang.nama as nama_barang', 'barang.kode as kode_barang', 'users.name as nama_peminjam')
            ->where(function($q) use ($query) {
                $q->where('barang.nama', 'LIKE', "%{$query}%")
                  ->orWhere('barang.kode', 'LIKE', "%{$query}%")
                  ->orWhere('users.name', 'LIKE', "%{$query}%")
                  ->orWhere('peminjaman.status', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get();

        $results['transaksi'] = $transaksiResults->map(function($item) {
            return [
                'id' => $item->id,
                'type' => 'transaksi',
                'title' => $item->nama_barang,
                'subtitle' => $item->nama_peminjam,
                'description' => 'Status: ' . ucfirst($item->status),
                'url' => route('admin.transaksi.show', $item->id),
                'icon' => 'fas fa-exchange-alt'
            ];
        });

        // Search Pengguna
        $penggunaResults = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        $results['pengguna'] = $penggunaResults->map(function($item) {
            return [
                'id' => $item->id,
                'type' => 'pengguna',
                'title' => $item->name,
                'subtitle' => $item->email,
                'description' => 'Role: ' . ucfirst($item->role ?? 'user'),
                'url' => route('admin.pengguna.show', $item->id),
                'icon' => 'fas fa-user'
            ];
        });

        // Search Kategori
        $kategoriResults = Kategori::where('nama', 'LIKE', "%{$query}%")
            ->orWhere('deskripsi', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        $results['kategori'] = $kategoriResults->map(function($item) {
            return [
                'id' => $item->id,
                'type' => 'kategori',
                'title' => $item->nama,
                'subtitle' => $item->deskripsi,
                'description' => 'Kategori barang',
                'url' => route('admin.kategori.show', $item->id),
                'icon' => 'fas fa-tag'
            ];
        });

        // Search Ruangan
        $ruanganResults = Ruangan::where('nama', 'LIKE', "%{$query}%")
            ->orWhere('lokasi', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        $results['ruangan'] = $ruanganResults->map(function($item) {
            return [
                'id' => $item->id,
                'type' => 'ruangan',
                'title' => $item->nama,
                'subtitle' => $item->lokasi,
                'description' => 'Ruangan penyimpanan',
                'url' => route('admin.ruangan.show', $item->id),
                'icon' => 'fas fa-building'
            ];
        });

        // Combine all results and sort by relevance
        $allResults = collect();
        foreach ($results as $type => $items) {
            $allResults = $allResults->merge($items);
        }

        return response()->json([
            'success' => true,
            'data' => $allResults->take(10)->values(),
            'total' => $allResults->count(),
            'query' => $query
        ]);
    }

    public function quickSearch(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        
        if (empty($query)) {
            return response()->json(['success' => false, 'message' => 'Query tidak boleh kosong']);
        }

        $results = [];

        switch ($type) {
            case 'barang':
                $results = Barang::with(['kategori', 'ruangan'])
                    ->where('nama', 'LIKE', "%{$query}%")
                    ->orWhere('kode', 'LIKE', "%{$query}%")
                    ->limit(10)
                    ->get();
                break;
                
            case 'transaksi':
                $results = DB::table('peminjaman')
                    ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
                    ->join('users', 'peminjaman.user_id', '=', 'users.id')
                    ->select('peminjaman.*', 'barang.nama as nama_barang', 'users.name as nama_peminjam')
                    ->where('barang.nama', 'LIKE', "%{$query}%")
                    ->orWhere('users.name', 'LIKE', "%{$query}%")
                    ->limit(10)
                    ->get();
                break;
                
            default:
                // Search all
                $barang = Barang::where('nama', 'LIKE', "%{$query}%")->limit(3)->get();
                $transaksi = DB::table('peminjaman')
                    ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
                    ->where('barang.nama', 'LIKE', "%{$query}%")
                    ->limit(3)
                    ->get();
                    
                $results = [
                    'barang' => $barang,
                    'transaksi' => $transaksi
                ];
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $results,
            'query' => $query
        ]);
    }
}
