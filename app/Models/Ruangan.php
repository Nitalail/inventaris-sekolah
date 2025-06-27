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
        'kapasitas', 
        'jumlah_barang', 
        'status', 
        'deskripsi'
    ];

    // Jika relasi one-to-many dengan Barang
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'ruangan_id');
    }
}