<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPanggilan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pengaduan_id',
        'nama_pihak',
        'status_pihak',
        'peran_pihak',
        'nim',
        'semester',
        'program_studi',
        'nip',
        'fakultas',
        'asal_instansi',
        'info_tambahan',
        'tanggal_panggilan',
        'waktu_panggilan',
        'tempat_panggilan',
        'file_name',
        'pdf_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'info_tambahan' => 'array',
    ];

    /**
     * Get the pengaduan that owns the surat panggilan.
     */
    public function pengaduan(): BelongsTo
    {
        return $this->belongsTo(Pengaduan::class);
    }
}
