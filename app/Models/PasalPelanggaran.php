<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasalPelanggaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'jenis_kekerasan',
        'pasal',
        'ayat',
        'butir',
        'keterangan',
    ];
    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }
}
