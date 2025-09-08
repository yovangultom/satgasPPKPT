<?php

namespace App\Filament\Resources\PengaduanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Pengaduan;
use App\Models\PasalPelanggaran;
use App\Models\LaporanHasilPemeriksaan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Set;
use App\Notifications\PengaduanStatusUpdated;
use Illuminate\Support\Facades\Log;


class LaporanHasilPemeriksaansRelationManager extends RelationManager
{
    protected static string $relationship = 'laporanHasilPemeriksaans';
    protected static ?string $title = 'Laporan Hasil Pemeriksaan';

    public function form(Form $form): Form
    {
        /** @var Pengaduan $pengaduan */
        $pengaduan = $this->ownerRecord;
        $jenisKekerasan = $pengaduan->jenis_kejadian;

        return $form
            ->schema([
                Select::make('berita_acara_pemeriksaan_id')
                    ->label('Dasar Berita Acara Pemeriksaan (BAP)')
                    ->relationship('beritaAcaraPemeriksaan', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => "BAP untuk: {$record->pihak_diperiksa_nama} ({$record->created_at->format('d M Y')})")
                    ->options(
                        $pengaduan->beritaAcaraPemeriksaans()->get()->mapWithKeys(
                            fn($bap) =>
                            [$bap->id => "BAP untuk: {$bap->pihak_diperiksa_nama} ({$bap->created_at->format('d M Y')})"]
                        )
                    )
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (callable $set, $state) use ($pengaduan) {
                        $bap = $pengaduan->beritaAcaraPemeriksaans()->find($state);
                        if ($bap) {
                            $set('pihak_diperiksa_nama', $bap->pihak_diperiksa_nama);
                            $set('pihak_diperiksa_ttl', $bap->pihak_diperiksa_tempat_lahir . ', ' . \Carbon\Carbon::parse($bap->pihak_diperiksa_tanggal_lahir)->translatedFormat('j F Y'));
                            $set('pihak_diperiksa_agama', $bap->pihak_diperiksa_agama);
                            $set('pihak_diperiksa_jenis_kelamin', $bap->pihak_diperiksa_jenis_kelamin);
                            $set('pihak_diperiksa_alamat', $bap->pihak_diperiksa_alamat);
                        }
                        $pelanggaranData = [];
                        if (!empty($bap->jenis_kejadian_awal)) {
                            foreach ($bap->jenis_kejadian_awal as $jenis) {
                                $pelanggaranData[] = ['jenis_kejadian' => $jenis, 'pasal_pelanggaran_ids' => []];
                            }
                        }
                        $set('pelanggaran_data', $pelanggaranData);
                    }),

                Fieldset::make('Data Pihak Terperiksa (Otomatis dari BAP)')
                    ->schema([
                        TextInput::make('pihak_diperiksa_nama')->label('Nama')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('pihak_diperiksa_ttl')->label('Tempat, Tanggal Lahir')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('pihak_diperiksa_agama')->label('Agama')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('pihak_diperiksa_jenis_kelamin')->label('Jenis Kelamin')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('pihak_diperiksa_alamat')->label('Alamat')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])->columns(2),

                Fieldset::make('Hasil Pemeriksaan')
                    ->schema([
                        Repeater::make('pelanggaran_data')
                            ->label('Pelanggaran yang Terbukti')
                            ->schema([
                                TextInput::make('jenis_kejadian')
                                    ->label('Jenis Kejadian')
                                    ->required(),
                                Select::make('pasal_pelanggaran_ids')
                                    ->label('Ketentuan yang Dilanggar')
                                    ->multiple()
                                    ->options(function (Get $get) {
                                        $jenisKekerasan = $get('jenis_kejadian');
                                        if (empty($jenisKekerasan)) {
                                            return [];
                                        }
                                        return PasalPelanggaran::where('jenis_kekerasan', $jenisKekerasan)->pluck('keterangan', 'id');
                                    })
                                    ->searchable()
                                    ->required(),
                            ])
                            ->columnSpanFull()
                            ->addable(false)
                            ->deletable(false),
                        Textarea::make('pembuktian_dan_analisis')->rows(5)->columnSpanFull()->required(),
                        Textarea::make('ringkasan_pemeriksaan')->rows(5)->columnSpanFull()->required(),
                        Textarea::make('pendampingan_diberikan')->label('Pendampingan, pelindungan, dan/atau pemulihan yang telah diberikan')->rows(5)->columnSpanFull()->required(),
                        Radio::make('status_terbukti')
                            ->options([
                                'terbukti' => 'Terbukti',
                                'tidak_terbukti' => 'Tidak Terbukti',
                            ])->required(),
                    ])
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitle('Laporan Hasil Pemeriksaan')
            ->columns([
                Tables\Columns\TextColumn::make('beritaAcaraPemeriksaan.pihak_diperiksa_nama')->label('BAP Untuk'),
                Tables\Columns\TextColumn::make('status_terbukti')->badge()->color(fn($state) => $state === 'terbukti' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('user.name')->label('Dibuat oleh'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime(),

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Laporan Hasil Pemeriksaan')
                    ->modalHeading('Laporan Hasil Pemeriksaan')
                    ->createAnother(false)
                    ->modalSubmitActionLabel('Save')
                    ->authorize(true)
                    ->closeModalByClickingAway(false)
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = Auth::id();
                        return $data;
                    })->after(function () {
                        /** @var \App\Models\Pengaduan $pengaduan */
                        $pengaduan = $this->getOwnerRecord();
                        $pengaduan->update(['status_pengaduan' => 'Penyusunan Kesimpulan dan Rekomendasi']);

                        // Muat ulang record untuk mendapatkan status terbaru
                        $pengaduan->refresh();

                        $user = $pengaduan->user;
                        if ($user) {
                            Log::info("Mempersiapkan notifikasi perubahan status (Penyusunan Kesimpulan) untuk User ID: {$user->id}, Email: {$user->email}");
                            $user->notify(new PengaduanStatusUpdated($pengaduan));
                            Log::info("Notifikasi untuk User ID: {$user->id} berhasil di-dispatch ke queue.");
                        } else {
                            Log::warning("Gagal mengirim notifikasi status (Penyusunan Kesimpulan): User tidak ditemukan untuk Pengaduan ID: {$pengaduan->id}");
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('LHP Berhasil Dibuat')
                            // --- Pesan notifikasi diperbarui ---
                            ->body('Status pengaduan telah diperbarui dan notifikasi telah dikirim ke pengguna.')
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading('Laporan Hasil Pemeriksaan')
                    ->form(fn(Form $form) => $this->form($form))
                    ->mountUsing(function (Form $form, Model $record) {
                        $form->fill($this->fillInitialData($record));
                    })
                    ->disabledForm(true)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->closeModalByClickingAway(false),
                Tables\Actions\Action::make('export_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(function (LaporanHasilPemeriksaan $record) {
                        return route('lhp.export.pdf', ['lhp' => $record]);
                    })
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->authorize(true)
                    ->label('Hapus'),
            ]);
    }

    protected function fillInitialData(LaporanHasilPemeriksaan $record): array
    {
        $formData = $record->toArray();
        $pengaduan = $this->ownerRecord;



        if ($record->berita_acara_pemeriksaan_id) {
            $bap = $pengaduan->beritaAcaraPemeriksaans()->find($record->berita_acara_pemeriksaan_id);
            if ($bap) {
                $formData['pihak_diperiksa_nama'] = $bap->pihak_diperiksa_nama;
                $formData['pihak_diperiksa_ttl'] = $bap->pihak_diperiksa_tempat_lahir . ', ' . \Carbon\Carbon::parse($bap->pihak_diperiksa_tanggal_lahir)->translatedFormat('j F Y');
                $formData['pihak_diperiksa_agama'] = $bap->pihak_diperiksa_agama;
                $formData['pihak_diperiksa_jenis_kelamin'] = $bap->pihak_diperiksa_jenis_kelamin;
                $formData['pihak_diperiksa_alamat'] = $bap->pihak_diperiksa_alamat;
            }
        }

        return $formData;
    }
}
