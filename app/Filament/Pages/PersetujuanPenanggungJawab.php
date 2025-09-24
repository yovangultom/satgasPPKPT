<?php

namespace App\Filament\Pages;

use App\Filament\Resources\PengaduanResource;
use App\Models\SuratRekomendasi;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PersetujuanPenanggungJawab extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.persetujuan-penanggung-jawab';
    protected static bool $shouldRegisterNavigation = false;

    // Rute definitif untuk halaman ini
    protected static string $routePath = 'surat-rekomendasi/persetujuan-pj/{record}';

    public SuratRekomendasi $record;
    public array $data = []; // Properti untuk menampung data form

    public function mount(int $record): void
    {
        // TES DIAGNOSTIK: Hentikan dan tampilkan apa yang ditemukan di database.
        dd(SuratRekomendasi::find($record));

        // Kode di bawah ini untuk sementara tidak akan berjalan
        $this->record = SuratRekomendasi::findOrFail($record);

        abort_unless(
            auth()->user()->hasRole('penanggung jawab') && $this->record->status_penanggung_jawab === 'Menunggu Persetujuan',
            403,
            'Akses Ditolak: Anda tidak memiliki izin atau surat ini sudah diproses.'
        );

        $this->form->fill([
            'komentar' => $this->record->komentar_penanggung_jawab,
        ]);
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('komentar')
                    ->label('Komentar / Alasan Penolakan')
                    ->rows(5)
                    ->helperText('Wajib diisi jika surat ditolak.'),
            ])
            ->statePath('data'); // Mengarahkan data form ke properti $data
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Setujui dan Teruskan ke Rektor')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->modalHeading('Setujui Surat Rekomendasi')
                ->modalDescription('Apakah Anda yakin? Tindakan ini akan meneruskan proses ke Rektor.')
                ->action(function (): void {
                    $this->record->update([
                        'status_penanggung_jawab' => 'Disetujui',
                        'komentar_penanggung_jawab' => $this->data['komentar'],
                        'tanggal_respon_penanggung_jawab' => now(),
                        'penanggung_jawab_id' => Auth::id(),
                        'status_rektor' => 'Menunggu Persetujuan',
                    ]);

                    Notification::make()->success()->title('Surat berhasil disetujui!')->send();
                    $this->redirect(PengaduanResource::getUrl('view', ['record' => $this->record->pengaduan_id]));
                }),

            Action::make('reject')
                ->label('Tolak Surat Rekomendasi')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->modalHeading('Tolak Surat Rekomendasi')
                ->modalDescription('Pastikan Anda sudah mengisi alasan penolakan di kolom komentar.')
                ->action(function (): void {
                    $comment = $this->data['komentar'] ?? null;
                    if (empty($comment)) {
                        Notification::make()->danger()->title('Gagal!')->body('Komentar atau alasan penolakan wajib diisi.')->send();
                        return;
                    }

                    $this->record->update([
                        'status_penanggung_jawab' => 'Ditolak',
                        'komentar_penanggung_jawab' => $comment,
                        'tanggal_respon_penanggung_jawab' => now(),
                        'penanggung_jawab_id' => Auth::id(),
                    ]);

                    Notification::make()->success()->title('Surat berhasil ditolak!')->send();
                    $this->redirect(PengaduanResource::getUrl('view', ['record' => $this->record->pengaduan_id]));
                }),
        ];
    }

    public function getTitle(): string
    {
        return 'Persetujuan Surat Rekomendasi';
    }

    public function getSubheading(): ?string
    {
        return "Review dokumen untuk kasus #{$this->record->pengaduan->nomor_pengaduan}";
    }
}
