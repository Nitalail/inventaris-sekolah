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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'peminjaman_baru', 'pengembalian', 'terlambat'
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // data tambahan (peminjaman_id, user_id, dll)
            $table->boolean('is_read')->default(false);
            $table->string('notifiable_type')->nullable(); // untuk polymorphic (Admin, User)
            $table->unsignedBigInteger('notifiable_id')->nullable(); // ID dari notifiable
            $table->unsignedBigInteger('user_id')->nullable(); // user yang memicu notifikasi
            $table->unsignedBigInteger('peminjaman_id')->nullable(); // referensi ke peminjaman
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('peminjaman_id')->references('id')->on('peminjaman')->onDelete('cascade');
            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index(['is_read', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
