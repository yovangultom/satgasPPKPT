<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Pengaduan extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomor_pengaduan',
        'user_id',
        'tanggal_pelaporan',
        'jenis_kejadian',
        'tanggal_kejadian',
        'lokasi_kejadian',
        'terjadi_saat_tridharma',
        'jenis_tridharma',
        'terjadi_di_wilayah_kampus',
        'deskripsi_pengaduan',
        'alasan_pengaduan',
        'identifikasi_kebutuhan_korban',
        'tanda_tangan_pelapor',
        'tanda_tangan_pelapor_file',
        'bukti_pendukung',
        'url_bukti_tambahan',
        'status_pengaduan',
        'pasal_pelanggaran_id',

    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_pelaporan' => 'datetime',
        'tanggal_kejadian' => 'date',
        'terjadi_saat_tridharma' => 'boolean',
        'terjadi_di_wilayah_kampus' => 'boolean',
        'alasan_pengaduan' => 'array',
        'identifikasi_kebutuhan_korban' => 'array',

    ];
    /**
     * Get the user that owns the pengaduan (the submitter).
     */
    public function setTandaTanganPelaporAttribute($value)
    {
        $this->attributes['tanda_tangan_pelapor'] = $value;

        if (empty($value)) {
            if (!empty($this->tanda_tangan_pelapor_file)) {
                Storage::disk('public')->delete($this->tanda_tangan_pelapor_file);
            }
            $this->attributes['tanda_tangan_pelapor_file'] = null;
            return;
        }

        if (!Str::startsWith($value, 'data:image')) {

            return;
        }

        try {

            $base64_data = preg_replace('/^data:image\/(.*?);base64,/', '', $value);
            $base64_data = str_replace(' ', '+', $base64_data);
            $decodedImage = base64_decode($base64_data);

            if ($decodedImage === false) {
                throw new \Exception('Gagal melakukan decode base64 pada gambar tanda tangan.');
            }
            if (!empty($this->tanda_tangan_pelapor_file)) {
                Storage::disk('public')->delete($this->tanda_tangan_pelapor_file);
            }
            $img = Image::read($decodedImage);
            $img->trim();
            $img->pad((int)($img->width() * 1.1), (int)($img->height() * 1.1));
            $croppedImageContent = $img->toPng();
            $filename = 'signatures/pelapor-' . ($this->id ?? Str::uuid()) . '-' . time() . '.png';
            Storage::disk('public')->put($filename, $croppedImageContent);
            $this->attributes['tanda_tangan_pelapor_file'] = $filename;
        } catch (\Exception $e) {
            Log::error('Gagal memproses gambar tanda tangan: ' . $e->getMessage());
            $this->attributes['tanda_tangan_pelapor_file'] = null;
        }
    }

    public function getTandaTanganPelaporImageUrlAttribute()
    {
        if (empty($this->tanda_tangan_pelapor_file)) {
            return null;
        }

        return Storage::disk('public')->url($this->tanda_tangan_pelapor_file);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelapors()
    {
        return $this->belongsToMany(Pelapor::class, 'pengaduan_pelapor')
            ->withPivot('peran_dalam_pengaduan')
            ->withTimestamps();
    }

    public function korbans()
    {
        return $this->belongsToMany(Korban::class, 'pengaduan_korban')
            ->withTimestamps();
    }

    public function terlapors()
    {
        return $this->belongsToMany(Terlapor::class, 'pengaduan_terlapor')
            ->withTimestamps();
    }
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }
    public function statusHistories()
    {
        return $this->hasMany(StatusHistory::class);
    }
    protected static function booted(): void
    {
        static::created(function (Pengaduan $pengaduan) {
            $pengaduan->statusHistories()->create([
                'status' => $pengaduan->status_pengaduan,
            ]);
        });
    }
    public function borangPdfs()
    {
        return $this->hasMany(BorangPdf::class);
    }
    public function suratPanggilans(): HasMany
    {
        return $this->hasMany(SuratPanggilan::class);
    }
    public function borangPenanganans()
    {
        return $this->hasMany(BorangPenanganan::class);
    }
    public function beritaAcaraPemeriksaans(): HasMany
    {
        return $this->hasMany(BeritaAcaraPemeriksaan::class);
    }
    public function borangPemeriksaans()
    {
        return $this->hasMany(BorangPemeriksaan::class);
    }
    public function pasalPelanggaran()
    {
        return $this->belongsTo(PasalPelanggaran::class);
    }
    public function laporanHasilPemeriksaans()
    {
        return $this->hasMany(LaporanHasilPemeriksaan::class);
    }
    public function suratRekomendasis(): HasMany
    {
        return $this->hasMany(SuratRekomendasi::class);
    }
}
