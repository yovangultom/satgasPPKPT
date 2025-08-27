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
        Schema::create('terlapors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nomor_telepon')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('domisili');
            $table->boolean('memiliki_disabilitas')->default(false);
            $table->enum('status', ['Mahasiswa', 'Dosen', 'Tendik', 'Warga Kampus', 'Masyarakat Umum']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terlapors');
    }
};
