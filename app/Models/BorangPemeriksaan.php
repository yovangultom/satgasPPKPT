<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorangPemeriksaan extends Model
{
    use HasFactory;
    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'nama_pendamping_korban',
        'disabilitas_pendamping_korban',
        'narasi_disabilitas_pendamping',
        'saksi_info',
        'pemeriksa_info',
        'tanggal_pemeriksaan',
        'tempat_pemeriksaan',
        'relasi_terlapor_korban',
        'kronologi_pemeriksaan',
        'kebutuhan_mendesak_verifikasi',
        'pemeriksaan_bukti'
    ];
    protected $casts = ['saksi_info' => 'array', 'pemeriksa_info' => 'array'];

    public function pengaduan(): BelongsTo
    {
        return $this->belongsTo(Pengaduan::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
