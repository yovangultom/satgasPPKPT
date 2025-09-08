<?php

namespace App\Filament\Resources\PengaduanResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\PengaduanResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Pengaduan;
use App\Models\Pelapor;
use App\Models\Terlapor;
use App\Models\Korban;
use App\Filament\Resources\PengaduanResource\RelationManagers;
use App\Models\SuratPanggilan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\KetuaSatgas;
use App\Http\Controllers\PengaduanPdfController;
use App\Notifications\PengaduanStatusUpdated;
use Illuminate\Support\Facades\Log;

class SuratPanggilanRelationManager extends RelationManager
{
    protected static string $relationship = 'suratPanggilans';

    protected static ?string $title = 'Surat Pemanggilan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // 
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_pihak')
            ->columns([
                Tables\Columns\TextColumn::make('nama_pihak')
                    ->label('Nama Pihak'),
                Tables\Columns\TextColumn::make('peran_pihak')
                    ->label('Peran')
                    ->badge(),
                Tables\Columns\TextColumn::make('tanggal_panggilan')
                    ->label('Tanggal Pemanggilan')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('generateSuratPanggilan')
                    ->label('Buat Surat Panggilan')
                    ->icon('heroicon-o-document-arrow-down')
                    ->modalHeading('Buat Surat Panggilan Pemeriksaan')
                    ->closeModalByClickingAway(false)

                    ->form(function () {
                        /** @var Pengaduan $record */
                        $record = $this->ownerRecord;
                        $pelapors = $record->pelapors;
                        $terlapors = $record->terlapors;
                        $korbans = $record->korbans;
                        $pihakTerlibat = $pelapors->concat($terlapors)->concat($korbans);
                        $pihakTerlibat = $pihakTerlibat->unique(function ($pihak) {
                            return get_class($pihak) . '-' . $pihak->id;
                        });
                        $options = $pihakTerlibat->mapWithKeys(function ($pihak) use ($record) {
                            $modelType = class_basename(get_class($pihak));
                            $key = strtolower($modelType) . '-' . $pihak->id . '-' . strtolower($pihak->status ?? 'lainnya');
                            $peranLabel = $modelType;
                            if ($modelType === 'Pelapor') {
                                $pelaporInPengaduan = $record->pelapors()->find($pihak->id);
                                $peranInPivot = $pelaporInPengaduan->pivot->peran_dalam_pengaduan ?? null;
                                $peranLabel = $peranInPivot;
                            }
                            $value = $pihak->nama . ' (' . ucfirst($pihak->status ?? 'Lainnya') . ' - ' . ucfirst($peranLabel) . ')';
                            return [$key => $value];
                        })->all();

                        return [
                            Select::make('pihak_terpilih')->label('Pilih Pihak yang Akan Dipanggil')->options($options)->searchable()->required()->live(),
                            TextInput::make('nim')->label('NIM')->visible(fn(Get $get) => str_contains($get('pihak_terpilih') ?? '', 'mahasiswa')),
                            TextInput::make('semester')->label('Semester')->numeric()->visible(fn(Get $get) => str_contains($get('pihak_terpilih') ?? '', 'mahasiswa')),
                            TextInput::make('program_studi')->label('Program Studi')->visible(fn(Get $get) => str_contains($get('pihak_terpilih') ?? '', 'mahasiswa')),
                            TextInput::make('asal_instansi')->label('Asal Instansi')->visible(fn(Get $get) => str_contains($get('pihak_terpilih') ?? '', 'mahasiswa')),
                            TextInput::make('fakultas_mhs')->label('Fakultas')->visible(fn(Get $get) => str_contains($get('pihak_terpilih') ?? '', 'mahasiswa')),
                            TextInput::make('nip')->label('NIP/NIDN')->visible(fn(Get $get) => str_contains($get('pihak_terpilih') ?? '', 'dosen') || str_contains($get('pihak_terpilih') ?? '', 'tendik')),
                            TextInput::make('fakultas_dosen')->label('Fakultas/Unit Kerja')->visible(fn(Get $get) => str_contains($get('pihak_terpilih') ?? '', 'dosen') || str_contains($get('pihak_terpilih') ?? '', 'tendik')),
                            TextInput::make('asal_instansi_dosen_tendik')->label('Asal Instansi')->visible(fn(Get $get) => str_contains($get('pihak_terpilih') ?? '', 'dosen') || str_contains($get('pihak_terpilih') ?? '', 'tendik')),
                            KeyValue::make('info_tambahan')->label('Informasi Tambahan')->keyLabel('Label')->valueLabel('Isi')->visible(fn(Get $get) => str_contains($get('pihak_terpilih') ?? '', 'warga') || str_contains($get('pihak_terpilih') ?? '', 'masyarakat')),
                            DatePicker::make('tanggal')->label('Hari/Tanggal')->required()->native(false),
                            TimePicker::make('waktu')->label('Waktu')->required()->seconds(false)->native(false)->displayFormat('H:i'),
                            TextInput::make('tempat')->label('Tempat')->required()->columnSpanFull(),
                        ];
                    })
                    ->action(function (array $data, PengaduanPdfController $controller) {
                        /** @var Pengaduan $record */
                        $record = $this->ownerRecord;

                        list($modelType, $pihakId, $status) = explode('-', $data['pihak_terpilih']);
                        $pihak = null;
                        if ($modelType === 'pelapor') {
                            $pihak = Pelapor::find($pihakId);
                        } elseif ($modelType === 'terlapor') {
                            $pihak = Terlapor::find($pihakId);
                        } elseif ($modelType === 'korban') {
                            $pihak = Korban::find($pihakId);
                        }
                        if (!$pihak) {
                            Notification::make()->title('Error')->body('Pihak yang dipilih tidak ditemukan.')->danger()->send();
                            return;
                        }
                        $peranText = '';
                        if ($modelType === 'pelapor') {
                            $pelaporInPengaduan = $record->pelapors()->find($pihakId);
                            $peranText = $pelaporInPengaduan->pivot->peran_dalam_pengaduan ?? null;
                        } else {
                            $peranText = ucfirst($modelType);
                        }
                        $data['fakultas'] = $data['fakultas_mhs'] ?? $data['fakultas_dosen'] ?? null;
                        $controller->generateSuratPanggilan($record, $pihak, $status, $peranText, $data);

                        $record->update(['status_pengaduan' => 'Verifikasi']);
                        $record->refresh();

                        $user = $record->user;
                        if ($user) {
                            Log::info("Mempersiapkan notifikasi perubahan status (Verifikasi) untuk User ID: {$user->id}, Email: {$user->email}");
                            $user->notify(new PengaduanStatusUpdated($record));
                            Log::info("Notifikasi untuk User ID: {$user->id} berhasil di-dispatch ke queue.");
                        } else {
                            Log::warning("Gagal mengirim notifikasi status (Verifikasi): User tidak ditemukan untuk Pengaduan ID: {$record->id}");
                        }
                        Notification::make()
                            ->title('Surat Panggilan Berhasil Dibuat')
                            ->body('Status pengaduan telah diperbarui menjadi "Verifikasi" dan notifikasi telah dikirim ke pengguna.')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->authorize(true)
                    ->url(fn($record) => Storage::disk('public')->url($record->pdf_path), shouldOpenInNewTab: true)
                    ->visible(fn($record) => $record->pdf_path && Storage::disk('public')->exists($record->pdf_path)),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->authorize(true),
            ])
            ->bulkActions([
                //
            ]);
    }
}
