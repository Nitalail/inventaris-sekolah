<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'barang_id',
        'user_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'keperluan',
        'status',
    ];

    public $timestamps = true;

    // Relasi ke model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
