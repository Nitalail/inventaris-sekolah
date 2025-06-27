<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->integer('jumlah');
            $table->date('tanggal_pinjam'); // Disesuaikan dengan validasi
            $table->date('tanggal_kembali');
            $table->text('keperluan');
            $table->text('catatan')->nullable(); // Kolom catatan ditambahkan
            $table->enum('status', ['pending', 'dipinjam', 'dikembalikan', 'terlambat', 'rusak'])->default('pending');

            // Kolom untuk fitur perpanjangan
            $table->integer('extended_count')->default(0);
            $table->string('extension_reason')->nullable();
            $table->timestamp('extended_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
};