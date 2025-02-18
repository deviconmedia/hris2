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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('telepon')->unique();
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('npwp')->nullable();
            $table->string('alamat');
            $table->date('tgl_gabung');
            $table->foreignId('divisi_id')->nullable()->constrained('divisi');
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatan');
            $table->boolean('status')->default(true);
            $table->string('image_uri')->default('https://placehold.co/200');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
