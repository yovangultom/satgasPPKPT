<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Panel;
use Illuminate\Support\Facades\Storage;
use App\Notifications\CustomResetPasswordNotification;



class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tanda_tangan',
        'status',
        'nim',
        'nip',
        'foto_profil',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->foto_profil) {
            return Storage::disk('public')->url($this->foto_profil) . '?v=' . time();
        }

        return null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->email === 'yovan.119140131@student.itera.ac.id') {
            return true;
        }
        return $this->hasAnyRole(['admin', 'petugas', 'rektor', 'htl']);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
