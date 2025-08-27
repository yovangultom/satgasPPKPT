<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelapor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nama',
        'nomor_telepon',
        'peran',
        'jenis_kelamin',
        'domisili',
        'memiliki_disabilitas',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'memiliki_disabilitas' => 'boolean',
    ];

    // --- Tambahkan relasi di bawah ini ---

    /**
     * Get the user that owns the pelapor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The pengaduans that belong to the pelapor.
     */
    public function pengaduans()
    {
        return $this->belongsToMany(Pengaduan::class, 'pengaduan_pelapor')
            ->withPivot('peran_dalam_pengaduan')
            ->withTimestamps();
    }
}
