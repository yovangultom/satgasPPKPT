<?php

namespace App\Notifications;

use App\Filament\Resources\PengaduanResource;
use App\Models\Message;
use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Pengaduan $pengaduan;
    public Message $message;

    public function __construct(Pengaduan $pengaduan, Message $message)
    {
        $this->pengaduan = $pengaduan;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('pengaduan.show', ['pengaduan' => $this->pengaduan->id]);
        $pengirim = $this->message->user->name;

        return (new MailMessage)
            ->subject('Pesan Baru pada Pengaduan Anda: ' . $this->pengaduan->nomor_pengaduan)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Anda menerima balasan baru pada laporan pengaduan Anda.')
            ->line('Pengirim: ' . $pengirim)
            ->line('Isi Pesan: "' . ($this->message->body ?? 'Lihat lampiran.') . '"')
            ->action('Lihat Detail Pengaduan', $url)
            ->salutation('Hormat kami, SATGAS PPKPT ITERA');
    }
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Pesan Baru dari ' . $this->message->user->name,
            'body' => 'Anda menerima balasan pada pengaduan: ' . $this->pengaduan->nomor_pengaduan,
            'icon' => 'heroicon-o-chat-bubble-left-right',
            'icon_color' => 'primary',
            'actions' => [
                [
                    'label' => 'Lihat Pesan',
                    'url' => PengaduanResource::getUrl('view', ['record' => $this->pengaduan->id]),
                    'mark_as_read' => true,
                ],
            ],
        ];
    }
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pesan Baru Diterima',
            'body' => 'Anda menerima pesan baru dari ' . $this->message->user->name . ' pada pengaduan #' . $this->pengaduan->nomor_pengaduan . '.',
            'url' => route('pengaduan.show', $this->pengaduan->id),
        ];
    }
}
