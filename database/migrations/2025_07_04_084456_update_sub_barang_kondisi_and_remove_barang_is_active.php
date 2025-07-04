<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update kondisi enum di tabel sub_barang untuk menambahkan 'nonaktif'
        Schema::table('sub_barang', function (Blueprint $table) {
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'nonaktif'])->change();
        });

        // 2. Hapus kolom is_active dari tabel barang
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Kembalikan kolom is_active ke tabel barang
        Schema::table('barang', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('sumber_dana');
        });

        // 2. Kembalikan kondisi enum di tabel sub_barang
        Schema::table('sub_barang', function (Blueprint $table) {
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->change();
        });
    }
};
