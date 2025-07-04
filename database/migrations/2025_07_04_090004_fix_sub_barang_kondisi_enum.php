<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the existing enum and recreate it with the correct values
        DB::statement("ALTER TABLE sub_barang MODIFY COLUMN kondisi ENUM('baik', 'rusak_ringan', 'rusak_berat', 'nonaktif') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE sub_barang MODIFY COLUMN kondisi ENUM('baik', 'rusak_ringan', 'rusak_berat') NOT NULL");
    }
};
