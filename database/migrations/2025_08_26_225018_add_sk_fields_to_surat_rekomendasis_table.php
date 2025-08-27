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
            $table->string('file_sk_path')->nullable()->after('komentar_rektor');
            $table->string('status_sk')->default('Menunggu Upload')->after('file_sk_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_rekomendasis', function (Blueprint $table) {
            $table->dropColumn('file_sk_path');
            $table->dropColumn('status_sk');
        });
    }
};
