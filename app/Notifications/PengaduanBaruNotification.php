<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use App\Filament\Resources\PengaduanResource; // Pastikan ini di-import
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action;

class PengaduanBaruNotification extends Notification
{
    use Queueable;

    protected $pengaduan;

    public function __construct(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Pengaduan Baru Diterima')
            ->icon('heroicon-o-document-text')
            ->body("Pengaduan baru dengan nomor {$this->pengaduan->nomor_pengaduan} telah diterima dan perlu diproses.")
            ->actions([
                Action::make('view')
                    ->label('Lihat Pengaduan')
                    ->url(PengaduanResource::getUrl('view', ['record' => $this->pengaduan])),
            ])
            ->getDatabaseMessage();
    }
}
