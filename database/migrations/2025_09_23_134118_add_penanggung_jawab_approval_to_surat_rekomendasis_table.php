<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('surat_rekomendasis', function (Blueprint $table) {
            $table->string('status_penanggung_jawab')->default('Menunggu Persetujuan')->after('status_terbukti');
            $table->text('komentar_penanggung_jawab')->nullable()->after('status_penanggung_jawab');
            $table->timestamp('tanggal_respon_penanggung_jawab')->nullable()->after('komentar_penanggung_jawab');
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('users')->after('tanggal_respon_penanggung_jawab');

            $table->string('status_rektor')->default('Belum Diproses')->change();
        });
    }

    public function down(): void
    {
        Schema::table('surat_rekomendasis', function (Blueprint $table) {
            //
        });
    }
};
