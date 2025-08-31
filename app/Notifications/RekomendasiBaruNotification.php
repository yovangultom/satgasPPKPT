<?php

namespace App\Notifications;

use App\Filament\Resources\PersetujuanRektorResource;
use App\Models\SuratRekomendasi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action as NotificationAction;

class RekomendasiBaruNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public SuratRekomendasi $suratRekomendasi;

    /**
     * Create a new notification instance.
     */
    public function __construct(SuratRekomendasi $suratRekomendasi)
    {
        $this->suratRekomendasi = $suratRekomendasi;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // Kirim ke database (Filament) dan email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = PersetujuanRektorResource::getUrl('review', ['record' => $this->suratRekomendasi]);

        return (new MailMessage)
            ->subject('Permohonan Persetujuan Surat Rekomendasi Baru')
            ->greeting('Yth. Bapak/Ibu Rektor,')
            ->line('Sebuah surat rekomendasi baru telah diajukan dan memerlukan persetujuan Anda.')
            ->line('Nomor Surat: ' . $this->suratRekomendasi->nomor_surat)
            ->action('Tinjau Surat Rekomendasi', $url)
            ->salutation('Hormat kami, Tim SATGAS PPKPT ITERA');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable)
    {
        return FilamentNotification::make()
            ->title('Persetujuan Surat Rekomendasi Baru')
            ->icon('heroicon-o-document-check')
            ->body("Surat Rekomendasi No. {$this->suratRekomendasi->nomor_surat} memerlukan persetujuan Anda.")
            ->actions([
                NotificationAction::make('view')
                    ->label('Tinjau Sekarang')
                    ->url(PersetujuanRektorResource::getUrl('review', ['record' => $this->suratRekomendasi]))
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
