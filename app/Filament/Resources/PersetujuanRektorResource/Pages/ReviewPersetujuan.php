<?php

namespace App\Filament\Resources\PersetujuanRektorResource\Pages;

use App\Filament\Resources\PersetujuanRektorResource;
use App\Filament\Resources\SuratKeputusanResource;
use App\Models\SuratRekomendasi;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Actions\Action as NotificationAction;


class ReviewPersetujuan extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string $resource = PersetujuanRektorResource::class;


    protected static string $view = 'filament.resources.persetujuan-rektor-resource.pages.review-persetujuan';

    public SuratRekomendasi $record;

    public function mount(): void
    {
        // 
    }

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
                ->modalDescription('Apakah Anda yakin ingin menyetujui rekomendasi ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Setujui')
                ->modalCancelActionLabel('Batal')
                ->action(function (array $data) {
                    $this->updateStatus('Disetujui', $data['komentar_setuju']);
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
                ->modalDescription('Pastikan Anda sudah mengisi alasan penolakan dengan jelas.')
                ->modalSubmitActionLabel('Ya, Tolak')
                ->modalCancelActionLabel('Batal')
                ->action(function (array $data) {
                    $this->updateStatus('Ditolak', $data['komentar_tolak']);
                }),
        ];
    }

    protected function updateStatus(string $status, ?string $comment = null): void
    {
        $this->record->update([
            'status_rektor' => $status,
            'komentar_rektor' => $comment,
            'tanggal_respon_rektor' => now(),
        ]);

        if ($status === 'Disetujui') {
            $htlUsers = User::role('htl')->get();
            $this->record->pengaduan()->update(['status_pengaduan' => 'Selesai']);


            if ($htlUsers->isNotEmpty()) {
                Notification::make()
                    ->title('Surat Rekomendasi Telah Disetujui')
                    ->body("Surat Rekomendasi No. {$this->record->nomor_surat} perlu penerbitan SK.")
                    ->actions([
                        NotificationAction::make('view')
                            ->label('Lihat Detail')
                            ->url(SuratKeputusanResource::getUrl('index'))
                    ])
                    ->sendToDatabase($htlUsers);

                // Logika email dinonaktifkan sementara
                // foreach ($htlUsers as $htlUser) {
                //     Mail::to($htlUser->email)->send(new SuratRekomendasiDisetujuiMail($this->record));
                // }
            }
        }
        Notification::make()
            ->title('Respon Berhasil Disimpan')
            ->body("Surat rekomendasi telah ditandai sebagai '$status'.")
            ->success()
            ->send();

        $this->redirect(PersetujuanRektorResource::getUrl('index'));
    }
}
