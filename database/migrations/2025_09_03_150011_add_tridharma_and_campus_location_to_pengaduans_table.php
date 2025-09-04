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
            $table->boolean('terjadi_saat_tridharma')->after('lokasi_kejadian')->default(false);
            $table->boolean('terjadi_di_wilayah_kampus')->after('terjadi_saat_tridharma')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn('terjadi_saat_tridharma');
            $table->dropColumn('terjadi_di_wilayah_kampus');
        });
    }
};
