<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'kondisi',
        'jumlah',
        'satuan',  
        'ruangan_id',
        'tahun_perolehan',
        'deskripsi',
        'sumber_dana',  // ganti dari 'keterangan'
    ];

    // Cast tipe data otomatis
    protected $casts = [
        'kategori_id' => 'integer',
        'jumlah' => 'integer',
        'ruangan_id' => 'integer',
        'tahun_perolehan' => 'integer',
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

    // Method untuk mendapatkan class badge bootstrap sesuai kondisi barang
    public function getKondisiBadgeClass()
    {
        return match ($this->kondisi) {
            'baik' => 'badge-success',
            'rusak_ringan' => 'badge-warning',
            'rusak_berat' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    // Accessor untuk menampilkan nama kondisi dengan format yang lebih baik
    public function getKondisiNameAttribute()
    {
        return match ($this->kondisi) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => $this->kondisi,
        };
    }
}
