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
        Schema::create('norma_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_cuti_id')->constrained('jenis_cuti');
            $table->foreignId('karyawan_id')->constrained('karyawan');
            $table->integer('jml_hari')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('norma_cuti');
    }
};
