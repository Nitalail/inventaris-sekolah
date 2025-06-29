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
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['kondisi', 'jumlah', 'tahun_perolehan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->after('kategori_id');
            $table->integer('jumlah')->after('kondisi');
            $table->year('tahun_perolehan')->after('ruangan_id');
        });
    }
};
