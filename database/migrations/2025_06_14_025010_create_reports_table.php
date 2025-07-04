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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');       
            $table->string('report_type');       
            $table->date('report_date');         
            $table->string('file_format');       
            $table->string('file_path');         
            $table->foreignId('user_id')         
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();                
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
