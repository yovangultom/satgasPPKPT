<?php

namespace App\Observers;

use App\Models\Pengaduan; // Pastikan ini di-import
use Illuminate\Support\Str; // Pastikan ini di-import
use App\Models\User; // Import model User
use App\Notifications\PengaduanBaruNotification; // Import class Notifikasi kita
use Illuminate\Support\Facades\Notification;

class PengaduanObserver
{
    /**
     * Handle the Pengaduan "creating" event.
     *
     * @param  \App\Models\Pengaduan  $pengaduan
     * @return void
     */
    public function creating(Pengaduan $pengaduan): void
    {
        do {
            $nomor = 'PGD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(3));
        } while (Pengaduan::where('nomor_pengaduan', $nomor)->exists());
        $pengaduan->nomor_pengaduan = $nomor;
    }

    /**
     * Handle the Pengaduan "created" event.
     */
    public function created(Pengaduan $pengaduan): void
    {
        $recipients = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin')->orWhere('name', 'petugas');
        })->get();
        Notification::send($recipients, new PengaduanBaruNotification($pengaduan));
    }

    /**
     * Handle the Pengaduan "updated" event.
     */
    public function updated(Pengaduan $pengaduan): void
    {
        //
    }

    /**
     * Handle the Pengaduan "deleted" event.
     */
    public function deleted(Pengaduan $pengaduan): void
    {
        //
    }

    /**
     * Handle the Pengaduan "restored" event.
     */
    public function restored(Pengaduan $pengaduan): void
    {
        //
    }

    /**
     * Handle the Pengaduan "force deleted" event.
     */
    public function forceDeleted(Pengaduan $pengaduan): void
    {
        //
    }
}
