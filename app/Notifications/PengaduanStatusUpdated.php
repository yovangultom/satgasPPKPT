<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

//                  👇 TAMBAHKAN BAGIAN INI
class PengaduanStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $pengaduan;

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
        $url = route('pengaduan.show', $this->pengaduan->id);

        return (new MailMessage)
            ->subject('Update Status Pengaduan Anda #' . $this->pengaduan->nomor_pengaduan)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Kami memberitahukan bahwa ada pembaruan pada status pengaduan Anda.')
            ->line('Status terbaru: **' . $this->pengaduan->status_pengaduan . '**')
            ->action('Lihat Detail Pengaduan', $url)
            ->salutation('Hormat kami, SATGAS PPKPT ITERA');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Status Pengaduan Diperbarui',
            'body' => 'Status pengaduan Anda #' . $this->pengaduan->nomor_pengaduan . ' telah diubah menjadi "' . $this->pengaduan->status_pengaduan . '".',
            'url' => route('pengaduan.show', $this->pengaduan->id),
        ];
    }
}
