<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasalSanksi extends Model
{
    use HasFactory;
    protected $fillable = ['jenis_sanksi', 'pelaku', 'pasal', 'ayat', 'butir', 'keterangan'];

    public function suratRekomendasis()
    {
        return $this->belongsToMany(\App\Models\SuratRekomendasi::class, 'surat_rekomendasi_pasal_sanksi');
    }
}
