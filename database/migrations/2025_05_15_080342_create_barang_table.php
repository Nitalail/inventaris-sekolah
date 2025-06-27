<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTable extends Migration
{
    public function up(): void
{
    Schema::create('barang', function (Blueprint $table) {
        $table->id();
        $table->string('kode', 20)->unique();
        $table->string('nama');
        $table->foreignId('kategori_id')->constrained()->onDelete('cascade');
        $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat']);
        $table->integer('jumlah');
        $table->string('satuan')->default('unit'); // kolom satuan ditambahkan
        $table->foreignId('ruangan_id')->constrained('ruangan')->onDelete('cascade');
        $table->year('tahun_perolehan');
        $table->text('deskripsi')->nullable();
        // Ganti keterangan jadi sumber_dana dengan enum
        $table->enum('sumber_dana', ['BOS', 'Komite', 'Bantuan Pemerintah', 'Hibah']);
        $table->timestamps();
    });
}



    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
}
