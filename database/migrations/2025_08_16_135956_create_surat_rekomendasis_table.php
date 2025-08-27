<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_rekomendasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->comment('Petugas yang membuat')->constrained();
            $table->string('nomor_surat')->nullable();

            // Kolom JSON untuk menyimpan array data pihak yang direkomendasikan
            $table->json('pihak_direkomendasi_data');

            // Kolom JSON untuk menyimpan array data pihak pelapor/korban
            $table->json('pihak_pelapor_data');

            $table->text('tembusan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_rekomendasis');
    }
};
