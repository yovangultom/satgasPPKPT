<?php

namespace App\Filament\Resources\PersetujuanPJResource\Pages;

use App\Filament\Resources\PersetujuanPJResource;
use App\Models\SuratRekomendasi;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action as NotificationAction;


class ViewPersetujuanPJ extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string $resource = PersetujuanPJResource::class;
    protected static ?string $pluralModelLabel = 'Persetujuan Surat Rekomendasi';
    protected static ?string $title = 'Tinjau Persetujuan Surat Rekomendasi';


    protected static string $view = 'filament.resources.persetujuan-pj-resource.pages.view-persetujuan-pj';

    public SuratRekomendasi $record;

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    protected function getActions(): array
    {
        return [
            Action::make('setujui')
                ->label('Setujui Rekomendasi')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('komentar_setuju')
                        ->label('Komentar atau Catatan (Opsional)')
                        ->rows(3),
                ])
                ->modalHeading('Setujui Rekomendasi')
                ->modalSubmitActionLabel('Ya, Setujui')
                ->action(function (array $data) {
                    $this->updateStatus('Disetujui', $data['komentar_setuju'] ?? null);
                }),

            Action::make('tolak')
                ->label('Tolak Rekomendasi')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('komentar_tolak')
                        ->label('Alasan Penolakan')
                        ->rows(3)
                        ->required(),
                ])
                ->modalHeading('Tolak Rekomendasi')
                ->modalSubmitActionLabel('Ya, Tolak')
                ->action(function (array $data) {
                    $this->updateStatus('Ditolak', $data['komentar_tolak']);
                }),
        ];
    }

    protected function updateStatus(string $status, ?string $comment = null): void
    {
        $this->record->update([
            'status_penanggung_jawab' => $status,
            'komentar_penanggung_jawab' => $comment,
            'tanggal_respon_penanggung_jawab' => now(),
        ]);
        $petugasPembuat = $this->record->user;

        if ($status === 'Disetujui') {
            $this->record->update(['status_rektor' => 'Menunggu Persetujuan']);
            $rektorUsers = User::role('rektor')->get();

            if ($rektorUsers->isNotEmpty()) {
                FilamentNotification::make()
                    ->title('Persetujuan Surat Rekomendasi Lanjutan')
                    ->body("Surat Rekomendasi No. {$this->record->nomor_surat} telah disetujui oleh PJ dan memerlukan persetujuan Anda.")
                    ->actions([
                        NotificationAction::make('view')
                            ->label('Lihat Detail')
                            ->url(fn() => \App\Filament\Resources\PersetujuanRektorResource::getUrl('index'))
                    ])
                    ->sendToDatabase($rektorUsers);
            }
        } elseif ($status === 'Ditolak') {
            if ($petugasPembuat) {
                FilamentNotification::make()
                    ->danger()
                    ->title('Surat Rekomendasi Ditolak oleh PJ')
                    ->body("Surat Rekomendasi No. {$this->record->nomor_surat} yang Anda buat telah ditolak oleh Penanggung Jawab.")
                    ->sendToDatabase($petugasPembuat);
            }
        }

        Notification::make()
            ->title('Respon Berhasil Disimpan')
            ->body("Status persetujuan telah ditandai sebagai '$status'.")
            ->success()
            ->send();

        $this->redirect(PersetujuanPJResource::getUrl('index'));
    }
}
