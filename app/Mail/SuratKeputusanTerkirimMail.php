<?php

namespace App\Mail;

use App\Models\SuratRekomendasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class SuratKeputusanTerkirimMail extends Mailable
{
    use Queueable, SerializesModels;

    public SuratRekomendasi $suratRekomendasi;
    public string $filePath;

    /**
     * Create a new message instance.
     */
    public function __construct(SuratRekomendasi $suratRekomendasi, string $filePath)
    {
        $this->suratRekomendasi = $suratRekomendasi;
        $this->filePath = $filePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Penerbitan Surat Keputusan (SK)',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.surat_keputusan_terkirim',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(Storage::disk('public')->path($this->filePath))
                ->as('Surat_Keputusan.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
