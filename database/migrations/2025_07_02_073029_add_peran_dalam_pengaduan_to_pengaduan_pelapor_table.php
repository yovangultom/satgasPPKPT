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
        Schema::table('pengaduan_pelapor', function (Blueprint $table) {
            $table->string('peran_dalam_pengaduan')->after('pelapor_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan_pelapor', function (Blueprint $table) {
            $table->dropColumn('peran_dalam_pengaduan');
        });
    }
};
