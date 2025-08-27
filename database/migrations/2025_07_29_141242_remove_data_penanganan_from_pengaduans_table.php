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
            $table->dropColumn('data_penanganan');
        });
    }
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->json('data_penanganan')->nullable();
        });
    }
};
