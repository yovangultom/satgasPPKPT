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
        Schema::table('surat_rekomendasis', function (Blueprint $table) {
            // Menambahkan kolom untuk status persetujuan dari Rektor.
            // Default 'Menunggu Persetujuan' akan otomatis diisi saat surat baru dibuat.
            $table->string('status_rektor')->default('Menunggu Persetujuan')->after('tembusan');

            // Menambahkan kolom untuk menyimpan komentar atau catatan dari Rektor.
            $table->text('komentar_rektor')->nullable()->after('status_rektor');

            // Menambahkan kolom untuk mencatat waktu Rektor memberikan respon.
            $table->timestamp('tanggal_respon_rektor')->nullable()->after('komentar_rektor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_rekomendasis', function (Blueprint $table) {
            $table->dropColumn([
                'status_rektor',
                'komentar_rektor',
                'tanggal_respon_rektor',
            ]);
        });
    }
};
