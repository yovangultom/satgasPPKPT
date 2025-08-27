<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAcaraPemeriksaan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'jenis_kejadian_awal',
        'pihak_diperiksa_tanggal_lahir' => 'date',
        'tanggal_kejadian' => 'date',
        'tanggal_pemeriksaan' => 'date',
        'waktu_pemeriksaan' => 'datetime:H:i',
        'anggota_satgas_ids' => 'array',
        'tanda_tangan_terperiksa',
    ];

    public function pengaduan(): BelongsTo
    {
        return $this->belongsTo(Pengaduan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
