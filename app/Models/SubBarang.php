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

    // Cast kondisi sebagai string
    protected $casts = [
        'kondisi' => 'string',
    ];

    // Scope untuk sub barang yang dapat dipinjam
    public function scopeAvailableForLoan($query)
    {
        return $query->whereIn('kondisi', ['baik', 'rusak_ringan']);
    }

    // Scope untuk sub barang yang tidak dapat dipinjam
    public function scopeNotAvailableForLoan($query)
    {
        return $query->whereIn('kondisi', ['rusak_berat', 'nonaktif']);
    }

    // Scope untuk sub barang aktif (tidak nonaktif)
    public function scopeActive($query)
    {
        return $query->where('kondisi', '!=', 'nonaktif');
    }

    // Scope untuk sub barang nonaktif
    public function scopeInactive($query)
    {
        return $query->where('kondisi', 'nonaktif');
    }

    // Method untuk nonaktifkan sub barang
    public function deactivate()
    {
        $this->update(['kondisi' => 'nonaktif']);
    }

    // Method untuk aktifkan sub barang (set ke kondisi baik)
    public function activate()
    {
        $this->update(['kondisi' => 'baik']);
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        switch ($this->kondisi) {
            case 'baik':
                return 'Baik';
            case 'rusak_ringan':
                return 'Rusak Ringan';
            case 'rusak_berat':
                return 'Rusak Berat';
            case 'nonaktif':
                return 'Nonaktif';
            default:
                return 'Tidak Diketahui';
        }
    }

    // Accessor untuk status class (untuk styling)
    public function getStatusClassAttribute()
    {
        switch ($this->kondisi) {
            case 'baik':
                return 'bg-green-100 text-green-700';
            case 'rusak_ringan':
                return 'bg-yellow-100 text-yellow-700';
            case 'rusak_berat':
                return 'bg-red-100 text-red-700';
            case 'nonaktif':
                return 'bg-gray-100 text-gray-700';
            default:
                return 'bg-gray-100 text-gray-700';
        }
    }

    // Method untuk cek apakah dapat dipinjam
    public function canBeLoaned()
    {
        return in_array($this->kondisi, ['baik', 'rusak_ringan']);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
