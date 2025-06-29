<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'kode_ruangan', 
        'nama_ruangan', 
        'status', 
        'deskripsi'
    ];

    // Jika relasi one-to-many dengan Barang
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'ruangan_id');
    }

    // Accessor untuk menghitung jumlah sub barang di ruangan ini
    public function getJumlahBarangAttribute()
    {
        return $this->barangs()
            ->withCount('subBarang')
            ->get()
            ->sum('sub_barang_count');
    }

    // Method untuk mendapatkan total sub barang (sama seperti accessor tapi bisa dipanggil langsung)
    public function getTotalSubBarang()
    {
        return $this->barangs()
            ->join('sub_barang', 'barang.id', '=', 'sub_barang.barang_id')
            ->count();
    }
}