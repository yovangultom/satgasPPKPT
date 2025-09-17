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
            $table->foreignId('user_id')->constrained(); 

            $table->string('nama_pendamping_korban')->nullable();
            $table->boolean('disabilitas_pendamping_korban')->default(false);
            $table->text('narasi_disabilitas_pendamping')->nullable();
            $table->json('saksi_info')->nullable(); 
            $table->json('pemeriksa_info')->nullable(); 
            $table->date('tanggal_pemeriksaan')->nullable();
            $table->string('tempat_pemeriksaan')->nullable();
            $table->text('relasi_terlapor_korban')->nullable();
            $table->text('kronologi_pemeriksaan')->nullable();
            $table->text('kebutuhan_mendesak_verifikasi')->nullable();
            $table->text('pemeriksaan_bukti')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borang_pemeriksaans');
    }
};
