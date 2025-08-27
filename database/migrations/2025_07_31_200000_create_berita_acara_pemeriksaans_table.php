<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acara_pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->comment('Petugas yang membuat BAP')->constrained();

            $table->string('pihak_diperiksa_nama');
            $table->string('pihak_diperiksa_peran');
            $table->string('pihak_diperiksa_jenis_kelamin');
            $table->string('jenis_kejadian_awal');

            $table->string('pihak_diperiksa_tempat_lahir');
            $table->date('pihak_diperiksa_tanggal_lahir');
            $table->string('pihak_diperiksa_agama');
            $table->text('pihak_diperiksa_alamat');
            $table->text('uraian_singkat_kejadian');
            $table->date('tanggal_kejadian');
            $table->string('tempat_kejadian');
            $table->string('saksi_pendamping')->nullable();

            $table->date('tanggal_pemeriksaan');
            $table->time('waktu_pemeriksaan');
            $table->string('tempat_pemeriksaan');
            $table->json('anggota_satgas_ids');
            $table->text('tanda_tangan_terperiksa')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acara_pemeriksaans');
    }
};
