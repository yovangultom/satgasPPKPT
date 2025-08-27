<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
     Schema::create('pemeriksaans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pengaduan_id')->constrained()->cascadeOnDelete();
        $table->foreignId('pihak_diperiksa_id')->comment('ID dari tabel pihak')->constrained('pihak')->cascadeOnDelete();
        $table->string('tempat_lahir');
        $table->date('tanggal_lahir');
        $table->string('agama');
        $table->text('uraian_kejadian');
        $table->string('saksi_pendamping')->nullable();
        $table->timestamps();
    });
}
