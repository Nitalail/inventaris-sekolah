<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubBarang extends Model
{
    
    protected $table = 'sub_barang';
    protected $fillable = [
        'barang_id',
        'kode',
        'kondisi',
        'tahun_perolehan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
