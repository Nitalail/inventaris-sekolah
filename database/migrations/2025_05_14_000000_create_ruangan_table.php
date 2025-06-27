<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuanganTable extends Migration
{
    /**
     * Jalankan migration: membuat tabel ruangans.
     */
   public function up()
{
    Schema::create('ruangan', function (Blueprint $table) {
        $table->id();
        $table->string('kode_ruangan', 10)->unique();
        $table->string('nama_ruangan');
        $table->integer('kapasitas');
        $table->integer('jumlah_barang')->default(0);
        $table->enum('status', ['aktif', 'perbaikan', 'tidak_aktif']);
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse migration: menghapus tabel ruangans.
     */
    public function down()
    {
        Schema::dropIfExists('ruangan'); // Sudah diperbaiki dari 'rooms' ke 'ruangans'
    }
}
