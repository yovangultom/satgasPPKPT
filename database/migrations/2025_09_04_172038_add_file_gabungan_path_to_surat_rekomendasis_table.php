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
            $table->string('file_gabungan_path')->nullable()->after('komentar_rektor');
        });
    }

    public function down(): void
    {
        Schema::table('surat_rekomendasis', function (Blueprint $table) {
            $table->dropColumn('file_gabungan_path');
        });
    }
};
