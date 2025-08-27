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
        Schema::create('pelapors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->string('nomor_telepon')->nullable();
            $table->enum('peran', ['Korban', 'Saksi']);
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
        Schema::dropIfExists('pelapors');
    }
};
