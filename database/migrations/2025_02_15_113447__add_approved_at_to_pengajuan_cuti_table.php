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
        Schema::table('pengajuan_cuti', function (Blueprint $table) {
            $table->foreignId('send_to')->nullable()->after('lampiran')->constrained('karyawan');
            $table->dateTime('approved_at')->nullable()->after('send_to');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('karyawan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_cuti', function (Blueprint $table) {
            $table->dropColumn('send_to');
            $table->dropColumn('approved_at');
            $table->dropColumn('approved_by');
        });
    }
};
