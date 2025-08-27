<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('borang_pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Petugas yang mengisi

            // Poin 4
            $table->string('nama_pendamping_korban')->nullable();
            // Poin 5
            $table->boolean('disabilitas_pendamping_korban')->default(false);
            $table->text('narasi_disabilitas_pendamping')->nullable();
            // Poin 6 & 7
            $table->json('saksi_info')->nullable(); // Untuk Repeater Saksi
            // Poin 8
            $table->json('pemeriksa_info')->nullable(); // Untuk Repeater Pemeriksa
            // Poin 9
            $table->date('tanggal_pemeriksaan')->nullable();
            // Poin 10
            $table->string('tempat_pemeriksaan')->nullable();
            // Poin 11
            $table->text('relasi_terlapor_korban')->nullable();
            // Poin 12
            $table->text('kronologi_pemeriksaan')->nullable();
            // Poin 13
            $table->text('kebutuhan_mendesak_verifikasi')->nullable();
            // Poin 14
            $table->text('pemeriksaan_bukti')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borang_pemeriksaans');
    }
};
