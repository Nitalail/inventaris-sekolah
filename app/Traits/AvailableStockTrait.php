<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait AvailableStockTrait
{
    /**
     * Get the query constraint for available sub barang
     * Excludes sub barang that are currently in active loans
     */
    protected function availableSubBarangConstraint($query)
    {
        return $query->whereIn('kondisi', ['baik', 'rusak_ringan'])
                     ->where('bisa_dipinjam', true)
                     ->whereNotExists(function ($subQuery) {
                         $subQuery->select(DB::raw(1))
                                  ->from('peminjaman')
                                  ->whereRaw('JSON_CONTAINS(peminjaman.sub_barang_ids, CAST(sub_barang.id as JSON))')
                                  ->whereIn('peminjaman.status', ['pending', 'dipinjam', 'dikonfirmasi']);
                     });
    }

    /**
     * Get available stock count for a specific barang
     */
    protected function getAvailableStockCount($barangId)
    {
        return \App\Models\SubBarang::where('barang_id', $barangId)
            ->where(function ($query) {
                $this->availableSubBarangConstraint($query);
            })
            ->count();
    }

    /**
     * Get barang with available sub barang count
     */
    protected function barangWithAvailableStock()
    {
        return \App\Models\Barang::withCount(['subBarang as available_sub_barang_count' => function ($query) {
            $this->availableSubBarangConstraint($query);
        }]);
    }
} 