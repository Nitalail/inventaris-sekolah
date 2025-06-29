<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use App\Models\User;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'barang_id',
        'user_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_kembali_aktual',
        'keperluan',
        'catatan',
        'status',
        'sub_barang_ids',
    ];

    protected $casts = [
        'sub_barang_ids' => 'array',
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_kembali_aktual' => 'datetime',
    ];

    public $timestamps = true;

    // Relasi ke model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
