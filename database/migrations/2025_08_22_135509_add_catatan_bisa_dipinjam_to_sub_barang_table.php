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
        Schema::table('sub_barang', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('tahun_perolehan')->comment('Catatan tentang sub barang');
            $table->boolean('bisa_dipinjam')->default(true)->after('catatan')->comment('Apakah sub barang bisa dipinjam (hanya bisa diubah jika kondisi rusak ringan)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_barang', function (Blueprint $table) {
            $table->dropColumn(['catatan', 'bisa_dipinjam']);
        });
    }
};
