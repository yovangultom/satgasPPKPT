<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pasal_pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kekerasan');
            $table->string('pasal')->nullable();
            $table->string('ayat')->nullable();
            $table->string('butir')->nullable();
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasal_pelanggarans');
    }
};
