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

            $table->foreignId('pengaduan_id')->constrained('pengaduans')->onDelete('cascade');

            $table->string('nama_pihak');
            $table->string('status_pihak'); 
            $table->string('peran_pihak'); 

            $table->string('nim')->nullable();
            $table->string('semester')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('nip')->nullable(); 
            $table->string('fakultas')->nullable();
            $table->string('asal_instansi')->nullable();
            $table->json('info_tambahan')->nullable();

            $table->date('tanggal_panggilan');
            $table->time('waktu_panggilan');
            $table->string('tempat_panggilan');

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
