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
        Schema::table('laporan_hasil_pemeriksaans', function (Blueprint $table) {
            $table->json('jenis_kekerasan')->nullable()->after('berita_acara_pemeriksaan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_hasil_pemeriksaans', function (Blueprint $table) {
            $table->dropColumn('jenis_kekerasan');
        });
    }
};
