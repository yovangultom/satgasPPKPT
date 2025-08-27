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
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->enum('status_pengaduan', [
                'Menunggu',
                'Verifikasi',
                'Investigasi',
                'Penyusunan Kesimpulan dan Rekomendasi',
                'Tindak Lanjut Kesimpulan dan Rekomendasi',
                'Selesai'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->string('status_pengaduan')->change();
        });
    }
};
