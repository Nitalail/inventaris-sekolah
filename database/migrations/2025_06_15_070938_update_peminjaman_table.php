<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('peminjaman', function (Blueprint $table) {
        // Jika belum ada kolom quantity
        if (!Schema::hasColumn('peminjaman', 'quantity')) {
            $table->integer('quantity')->default(1)->after('barang_id');
        }
        
        // Tambahkan kolom tanggal_kembali_rencana
        if (!Schema::hasColumn('peminjaman', 'tanggal_kembali_rencana')) {
            $table->date('tanggal_kembali_rencana')->after('tanggal_pinjam');
        }
    });
}

public function down()
{
    Schema::table('peminjaman', function (Blueprint $table) {
        $table->dropColumn(['quantity', 'tanggal_kembali_rencana']);
    });
}
};
