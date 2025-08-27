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
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengaduan')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('tanggal_pelaporan');
            $table->enum('jenis_kejadian', [
                'Kekerasan fisik',
                'Kekerasan psikis',
                'Perundungan',
                'Kekerasan seksual',
                'Kebijakan yang mengandung kekerasan',
                'Diskriminasi dan intoleransi'
            ]);
            $table->date('tanggal_kejadian');
            $table->string('lokasi_kejadian');
            $table->text('deskripsi_pengaduan');
            $table->text('alasan_pengaduan');
            $table->text('identifikasi_kebutuhan_korban')->nullable();
            $table->text('tanda_tangan_pelapor')->nullable();
            $table->string('bukti_pendukung')->nullable();
            $table->string('url_bukti_tambahan')->nullable();
            $table->enum('status_pengaduan', ['Pending', 'Verifikasi', 'Investigasi', 'Penyusunan Kesimpulan dan Rekomendasi', 'Tindak Lanjut Kesimpulan dan Rekomendasi', 'Selesai'])->default('Pending');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
