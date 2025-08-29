<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use App\Filament\Resources\PengaduanResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action;

class PengaduanBaruNotification extends Notification implements ShouldQueue

{
    use Queueable;

    protected $pengaduan;

    public function __construct(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = PengaduanResource::getUrl('view', ['record' => $this->pengaduan]);

        return (new MailMessage)
            ->subject('Pengaduan Baru Diterima: #' . $this->pengaduan->nomor_pengaduan)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line("Pengaduan baru dengan nomor {$this->pengaduan->nomor_pengaduan} telah diterima dan perlu diproses.")
            ->line("Pengadu: {$this->pengaduan->user->name}")
            ->action('Lihat Detail Pengaduan', $url)
            ->salutation('Hormat kami, SATGAS PPKPT ITERA');
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
