<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->foreignId('pasal_pelanggaran_id')->nullable()->after('status_pengaduan')->constrained('pasal_pelanggarans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropForeign(['pasal_pelanggaran_id']);
            $table->dropColumn('pasal_pelanggaran_id');
        });
    }
};
