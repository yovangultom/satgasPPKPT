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
        Schema::create('surat_panggilans', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel pengaduan utama
            $table->foreignId('pengaduan_id')->constrained('pengaduans')->onDelete('cascade');

            // Data utama pihak yang dipanggil
            $table->string('nama_pihak');
            $table->string('status_pihak'); // cth: mahasiswa, dosen, tendik, warga
            $table->string('peran_pihak'); // cth: Pelapor, Terlapor, Korban

            // Detail spesifik berdasarkan status pihak (bisa null)
            $table->string('nim')->nullable();
            $table->string('semester')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('nip')->nullable(); // Untuk Dosen/Tendik
            $table->string('fakultas')->nullable();
            $table->string('asal_instansi')->nullable();
            $table->json('info_tambahan')->nullable(); // Untuk Warga/Lainnya (tipe KeyValue)

            // Detail jadwal pemanggilan
            $table->date('tanggal_panggilan');
            $table->time('waktu_panggilan');
            $table->string('tempat_panggilan');

            // Informasi file PDF yang dihasilkan
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_panggilans');
    }
};
