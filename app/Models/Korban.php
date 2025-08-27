<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Korban extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'nomor_telepon',
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

    /**
     * The pengaduans that belong to the korban.
     */
    public function pengaduans()
    {
        return $this->belongsToMany(Pengaduan::class, 'pengaduan_korban')
            ->withTimestamps();
    }
}
