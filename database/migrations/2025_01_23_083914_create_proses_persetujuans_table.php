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
        Schema::create('proses_persetujuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained('divisi');
            $table->string('penerima');
            $table->string('penyetuju');
            $table->integer('max_hari');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proses_persetujuan');
    }
};
