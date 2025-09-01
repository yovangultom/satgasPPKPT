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
            // Menambahkan kolom baru untuk menyimpan data terstruktur
            $table->json('pelanggaran_data')->nullable()->after('berita_acara_pemeriksaan_id');

            // Menghapus kolom lama yang sudah tidak relevan
            if (Schema::hasColumn('laporan_hasil_pemeriksaans', 'jenis_kekerasan')) {
                $table->dropColumn('jenis_kekerasan');
            }
            if (Schema::hasColumn('laporan_hasil_pemeriksaans', 'pasal_pelanggaran_id')) {
                $table->dropColumn('pasal_pelanggaran_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_hasil_pemeriksaans', function (Blueprint $table) {
            $table->dropColumn('pelanggaran_data');
            // Menambahkan kembali kolom lama jika di-rollback
            $table->json('jenis_kekerasan')->nullable();
            $table->json('pasal_pelanggaran_id')->nullable();
        });
    }
};
