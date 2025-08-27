<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_rekomendasi_pasal_sanksi', function (Blueprint $table) {
            $table->foreignId('surat_rekomendasi_id')->constrained()->onDelete('cascade');
            $table->foreignId('pasal_sanksi_id')->constrained('pasal_sanksis')->onDelete('cascade');
            $table->primary(['surat_rekomendasi_id', 'pasal_sanksi_id'], 'surat_rekomendasi_pasal_sanksi_primary');
        });
    }
    // ... down method ...
};
