<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan_hasil_pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('berita_acara_pemeriksaan_id')->constrained('berita_acara_pemeriksaans')->onDelete('cascade');
            $table->json('pasal_pelanggaran_id')->nullable();
            $table->text('pembuktian_dan_analisis')->nullable();
            $table->text('ringkasan_pemeriksaan')->nullable();
            $table->text('pendampingan_diberikan')->nullable();
            $table->enum('status_terbukti', ['terbukti', 'tidak_terbukti'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_hasil_pemeriksaans');
    }
};
