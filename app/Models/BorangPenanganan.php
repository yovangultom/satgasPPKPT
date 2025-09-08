<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorangPenanganan extends Model
{
    use HasFactory;

    protected $fillable = ['pengaduan_id', 'user_id', 'deskripsi_pengaduan', 'pihak_yang_dihubungi', 'kerja_sama', 'pdf_path'];

    protected $casts = [
        'pihak_yang_dihubungi' => 'array',
        'pdf_path' => 'string',
    ];

    public function pengaduan(): BelongsTo
    {
        return $this->belongsTo(Pengaduan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
