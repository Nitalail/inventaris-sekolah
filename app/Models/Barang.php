<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Barang extends Model
{
    use HasFactory;

    // Nama tabel menggunakan bentuk tunggal sesuai permintaan
    protected $table = 'barang';

    // Mass assignable attributes
    protected $fillable = [
        'kode',
        'nama',
        'kategori_id',
        'satuan',  
        'ruangan_id',
        'deskripsi',
        'sumber_dana',  // ganti dari 'keterangan'
    ];

    // Cast tipe data otomatis
    protected $casts = [
        'kategori_id' => 'integer',
        'ruangan_id' => 'integer',
    ];

    // Relasi Barang ke Kategori (many-to-one)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi Barang ke Ruangan (many-to-one)
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    // Relasi Barang ke SubBarang (one-to-many)
    public function subBarang()
    {
        return $this->hasMany(SubBarang::class, 'barang_id');
    }

    // Scope untuk barang yang memiliki sub barang yang dapat dipinjam
    public function scopeAvailableForLoan($query)
    {
        return $query->whereHas('subBarang', function ($q) {
            $q->whereIn('kondisi', ['baik', 'rusak_ringan'])
            ->where('bisa_dipinjam', true);
        });
    }

    // Method untuk mendapatkan jumlah sub barang yang dapat dipinjam
    public function getAvailableSubBarangCountAttribute()
    {
        return $this->subBarang()
            ->whereIn('kondisi', ['baik', 'rusak_ringan'])
            ->where('bisa_dipinjam', true)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('peminjaman')
                    ->whereRaw('JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))')
                    ->whereIn('peminjaman.status', ['pending', 'dipinjam', 'dikonfirmasi']);
            })
            ->count();
    }
}
