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
            $table->json('email_penerima_sk')->nullable()->after('status_sk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_rekomendasis', function (Blueprint $table) {
            $table->dropColumn('email_penerima_sk');
        });
    }
};
