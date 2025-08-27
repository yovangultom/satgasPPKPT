<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SuratRekomendasi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'nomor_surat',
        'pihak_direkomendasi_data',
        'pihak_pelapor_data',
        'tembusan',
        'status_terbukti',
        'rekomendasi_data',
        'laporan_hasil_pemeriksaan_id',
        'status_rektor',
        'komentar_rektor',
        'tanggal_respon_rektor',
        'file_sk_path',
        'status_sk',
        'email_penerima_sk',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pihak_direkomendasi_data' => 'array',
        'pihak_pelapor_data' => 'array',
        'rekomendasi_data' => 'array',
        'email_penerima_sk' => 'array',


    ];

    public function pengaduan(): BelongsTo
    {
        return $this->belongsTo(Pengaduan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function sanksis(): BelongsToMany
    {
        return $this->belongsToMany(PasalSanksi::class, 'surat_rekomendasi_pasal_sanksi', 'surat_rekomendasi_id', 'pasal_sanksi_id');
    }
    public function laporanHasilPemeriksaan(): BelongsTo
    {
        return $this->belongsTo(LaporanHasilPemeriksaan::class);
    }
}
