<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanHasilPemeriksaan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'pasal_pelanggaran_id' => 'array',
    ];

    public function pengaduan(): BelongsTo
    {
        return $this->belongsTo(Pengaduan::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function beritaAcaraPemeriksaan(): BelongsTo
    {
        return $this->belongsTo(BeritaAcaraPemeriksaan::class);
    }
    public function pasalPelanggaran(): BelongsTo
    {
        return $this->belongsTo(PasalPelanggaran::class);
    }
}
