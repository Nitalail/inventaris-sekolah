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
        Schema::create('sub_barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat']);
            $table->year('tahun_perolehan');
            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_barang');
    }
};
