<?php

namespace App\Filament\Resources\PengaduanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Pengaduan;
use App\Models\LaporanHasilPemeriksaan;
use App\Models\PasalPelanggaran;
use App\Models\PasalSanksi;
use App\Models\SuratRekomendasi;
use App\Models\User;
use App\Notifications\RekomendasiBaruNotification;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Notification;

class SuratRekomendasisRelationManager extends RelationManager
{
    protected static string $relationship = 'suratRekomendasis';
    protected static ?string $title = 'Surat Rekomendasi';

    public function canView(Model $record): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'petugas']);
    }

    public function canDelete(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function form(Form $form): Form
    {
        /** @var Pengaduan $pengaduan */
        $pengaduan = $this->getOwnerRecord();

        $terlaporOptions = $pengaduan->terlapors->mapWithKeys(fn($p) => ['terlapor|' . $p->id => "{$p->nama} (Terlapor)"]);
        $pelaporOptions = $pengaduan->pelapors->mapWithKeys(fn($p) => ['pelapor|' . $p->id => "{$p->nama} (Pelapor)"]);
        $korbanOptions = $pengaduan->korbans->mapWithKeys(fn($p) => ['korban|' . $p->id => "{$p->nama} (Korban)"]);
        $pihakPelaporOptions = $pelaporOptions->merge($korbanOptions);

        return $form
            ->schema([
                Forms\Components\Select::make('laporan_hasil_pemeriksaan_id')
                    ->label('Dasar Laporan Hasil Pemeriksaan (LHP)')
                    ->options(
                        $pengaduan->laporanHasilPemeriksaans()->with('beritaAcaraPemeriksaan')->get()->mapWithKeys(
                            fn($lhp) => [$lhp->id => "LHP untuk: {$lhp->beritaAcaraPemeriksaan->pihak_diperiksa_nama} (" . ucfirst(str_replace('_', ' ', $lhp->status_terbukti)) . ")"]
                        )
                    )
                    ->live()
                    ->required()
                    ->searchable()
                    ->afterStateUpdated(function (Set $set, $state) use ($pengaduan) {
                        if (blank($state)) {
                            $set('status_terbukti', null);
                            $set('jenis_kekerasan', null);
                            $set('pasal_pelanggaran_display', null);
                            return;
                        };
                        $lhp = LaporanHasilPemeriksaan::find($state);
                        if ($lhp) {
                            $set('status_terbukti', $lhp->status_terbukti);
                            $set('jenis_kekerasan', $pengaduan->jenis_kejadian);
                            if ($lhp->pasal_pelanggaran_id) {
                                $pasalRecords = PasalPelanggaran::whereIn('id', $lhp->pasal_pelanggaran_id)->get();

                                $pasalText = $pasalRecords->map(function ($pasal) {
                                    $text = "Pasal {$pasal->pasal}";
                                    if ($pasal->ayat !== '(-)') {
                                        $text .= " Ayat {$pasal->ayat}";
                                    }
                                    if ($pasal->butir !== '-') {
                                        $text .= " Butir {$pasal->butir}";
                                    }
                                    $text .= ": {$pasal->keterangan}";
                                    return $text;
                                })->implode("\n");

                                $set('pasal_pelanggaran_display', $pasalText);
                            }
                        }
                    }),

                Forms\Components\Hidden::make('status_terbukti'),

                Forms\Components\Repeater::make('pihak_direkomendasi_data')
                    ->label('Pihak yang Direkomendasikan (Sebelumnya Terlapor)')
                    ->schema([
                        Forms\Components\Select::make('key')
                            ->label('Pilih Pihak')
                            ->options($terlaporOptions)
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (Set $set, ?string $state) use ($pengaduan) {
                                if (blank($state)) return;
                                [$tipe, $id] = explode('|', $state);
                                $pihak = $pengaduan->terlapors()->find($id);
                                if ($pihak) {
                                    $set('nama', $pihak->nama);
                                    $set('status', $pihak->status);
                                }
                            }),

                        Forms\Components\TextInput::make('nama')->label('Nama')->readOnly(),
                        Forms\Components\TextInput::make('status')->label('Status')->readOnly(),

                        Forms\Components\TextInput::make('nim')->label('NIM')->visible(fn(Get $get) => $get('status') === 'Mahasiswa'),
                        Forms\Components\TextInput::make('semester')->label('Semester')->visible(fn(Get $get) => $get('status') === 'Mahasiswa'),
                        Forms\Components\TextInput::make('prodi')->label('Program Studi')->visible(fn(Get $get) => $get('status') === 'Mahasiswa'),
                        Forms\Components\TextInput::make('fakultas')->label('Fakultas')->visible(fn(Get $get) => $get('status') === 'Mahasiswa'),
                        Forms\Components\TextInput::make('nip_nrk')->label('NIP/NRK')->visible(fn(Get $get) => in_array($get('status'), ['Dosen', 'Tendik'])),
                        Forms\Components\TextInput::make('fakultas_unit')->label('Fakultas/Lembaga/Unit')->visible(fn(Get $get) => in_array($get('status'), ['Dosen', 'Tendik'])),
                        Forms\Components\TextInput::make('nik')->label('NIK')->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum'])),
                        Forms\Components\TextInput::make('instansi')->label('Instansi')->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum'])),
                        Forms\Components\TextInput::make('keterangan')->label('Keterangan')->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum'])),
                    ])
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Pihak Terlapor')
                    ->collapsible(),

                Forms\Components\Repeater::make('pihak_pelapor_data')
                    ->label('Pihak Pelapor/Korban')
                    ->schema([
                        Forms\Components\Select::make('key')
                            ->label('Pilih Pihak')
                            ->options($pihakPelaporOptions)
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (Set $set, ?string $state) use ($pengaduan) {
                                if (blank($state)) return;
                                [$tipe, $id] = explode('|', $state);
                                $pihak = match ($tipe) {
                                    'pelapor' => $pengaduan->pelapors()->find($id),
                                    'korban' => $pengaduan->korbans()->find($id),
                                };
                                if ($pihak) {
                                    $set('nama', $pihak->nama);
                                    $set('status', $pihak->status);
                                }
                            }),

                        Forms\Components\TextInput::make('nama')->label('Nama')->readOnly(),
                        Forms\Components\TextInput::make('status')->label('Status')->readOnly(),

                        Forms\Components\TextInput::make('nim')->label('NIM')->visible(fn(Get $get) => $get('status') === 'Mahasiswa'),
                        Forms\Components\TextInput::make('semester')->label('Semester')->visible(fn(Get $get) => $get('status') === 'Mahasiswa'),
                        Forms\Components\TextInput::make('prodi')->label('Program Studi')->visible(fn(Get $get) => $get('status') === 'Mahasiswa'),
                        Forms\Components\TextInput::make('fakultas')->label('Fakultas')->visible(fn(Get $get) => $get('status') === 'Mahasiswa'),
                        Forms\Components\TextInput::make('nip_nrk')->label('NIP/NRK')->visible(fn(Get $get) => in_array($get('status'), ['Dosen', 'Tendik'])),
                        Forms\Components\TextInput::make('fakultas_unit')->label('Fakultas/Lembaga/Unit')->visible(fn(Get $get) => in_array($get('status'), ['Dosen', 'Tendik'])),
                        Forms\Components\TextInput::make('nik')->label('NIK')->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum'])),
                        Forms\Components\TextInput::make('instansi')->label('Instansi')->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum'])),
                        Forms\Components\TextInput::make('keterangan')->label('Keterangan')->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum'])),
                    ])
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Pihak Pelapor/Korban')
                    ->collapsible(),

                Forms\Components\Section::make('Rekomendasi Sanksi')
                    ->visible(fn(Get $get) => $get('status_terbukti') === 'terbukti')
                    ->schema([
                        Forms\Components\TextInput::make('jenis_kekerasan')
                            ->label('Jenis Kekerasan Sesuai Laporan')
                            ->readOnly()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make('pasal_pelanggaran_display')
                            ->label('Pasal Pelanggaran Sesuai LHP')
                            ->readOnly()
                            ->dehydrated(false)
                            ->rows(5),

                        Forms\Components\Select::make('status_pelaku_manual')
                            ->label('Pilih Status Pelaku (untuk Sanksi)')
                            ->options([
                                'Dosen ASN' => 'Dosen ASN',
                                'Dosen Non-ASN' => 'Dosen Non-ASN',
                                'Tenaga Kependidikan ASN' => 'Tenaga Kependidikan ASN',
                                'Tenaga Kependidikan Non-ASN' => 'Tenaga Kependidikan Non-ASN',
                                'Mahasiswa' => 'Mahasiswa',
                                'Warga Kampus' => 'Warga Kampus',
                                'Pimpinan Perguruan Tinggi' => 'Pimpinan Perguruan Tinggi',
                                'Pimpinan Perguruan Tinggi non-ASN' => 'Pimpinan Perguruan Tinggi non-ASN',
                            ])
                            ->helperText('Pilihan ini akan menentukan daftar sanksi yang tersedia.')
                            ->live()
                            ->required(),

                        Forms\Components\Select::make('jenis_sanksi')
                            ->label('Pilih Jenis Sanksi')
                            ->options([
                                'Ringan' => 'Ringan',
                                'Sedang' => 'Sedang',
                                'Berat' => 'Berat',
                            ])
                            ->live()
                            ->required(),

                        Forms\Components\Select::make('sanksi_ids')
                            ->label('Pilih Pasal Sanksi yang Direkomendasikan')
                            ->multiple()
                            ->relationship('sanksis', 'keterangan')
                            ->options(function (Get $get): array {
                                $statusPelaku = $get('status_pelaku_manual');
                                $jenisSanksi = $get('jenis_sanksi');

                                if (empty($statusPelaku) || empty($jenisSanksi)) {
                                    return [];
                                }

                                return PasalSanksi::where('pelaku', $statusPelaku)
                                    ->where('jenis_sanksi', $jenisSanksi)
                                    ->get()
                                    ->mapWithKeys(function ($sanksi) {
                                        $label = "Pasal {$sanksi->pasal} Ayat {$sanksi->ayat} Butir {$sanksi->butir} - {$sanksi->keterangan}";
                                        return [$sanksi->id => $label];
                                    })
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Textarea::make('sanksi_administratif')
                            ->label('Keterangan')
                            ->helperText('Keterangan tambahan.'),
                    ]),

                Forms\Components\Section::make('Rekomendasi Pemulihan')
                    ->visible(fn(Get $get) => $get('status_terbukti') === 'tidak_terbukti')
                    ->schema([
                        Forms\Components\Placeholder::make('pemulihan_nama_baik')
                            ->label('Tindakan')
                            ->content('Pemulihan nama baik terhadap yang bersangkutan (terlapor).'),
                    ]),

                Forms\Components\Textarea::make('tembusan')
                    ->label('Tembusan Surat')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nomor_surat')
            ->columns([
                Tables\Columns\TextColumn::make('laporanHasilPemeriksaan.beritaAcaraPemeriksaan.pihak_diperiksa_nama')
                    ->label('Untuk Pihak'),
                Tables\Columns\TextColumn::make('status_terbukti')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn($state) => $state === 'terbukti' ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('status_rektor')
                    ->label('Status Persetujuan')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Menunggu Persetujuan' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('komentar_rektor')
                    ->label('Komentar Rektor')
                    ->wrap(),

                Tables\Columns\TextColumn::make('tanggal_respon_rektor')
                    ->label('Tanggal Respon')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Dibuat oleh'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Surat Rekomendasi')
                    ->modalHeading('Surat Rekomendasi')
                    ->createAnother(false)
                    ->modalSubmitActionLabel('Save')
                    ->authorize(true)
                    ->closeModalByClickingAway(false)
                    ->mutateFormDataUsing(function (array $data): array {
                        $pengaduan = $this->getOwnerRecord();
                        $lhp = LaporanHasilPemeriksaan::find($data['laporan_hasil_pemeriksaan_id']);

                        $data['nomor_surat'] = $pengaduan->nomor_pengaduan;
                        $data['status_terbukti'] = $lhp->status_terbukti;

                        $processRepeaterData = function ($item) {
                            if (empty($item['key'])) {
                                return collect($item)->forget('key')->all();
                            }
                            [$tipe, $id] = explode('|', $item['key']);
                            $item['pihak_type'] = $tipe;
                            $item['pihak_id'] = $id;
                            return collect($item)->forget('key')->all();
                        };

                        $data['pihak_direkomendasi_data'] = array_map($processRepeaterData, $data['pihak_direkomendasi_data'] ?? []);
                        $data['pihak_pelapor_data'] = array_map($processRepeaterData, $data['pihak_pelapor_data'] ?? []);

                        if ($lhp && $lhp->status_terbukti === 'terbukti') {
                            $data['rekomendasi_data'] = [
                                'jenis_kekerasan' => $pengaduan->jenis_kejadian,
                                'pasal_pelanggaran_ids' => $lhp->pasal_pelanggaran_id,
                                'sanksi_administratif' => $data['sanksi_administratif'],
                                'status_pelaku_manual' => $data['status_pelaku_manual'],
                                'jenis_sanksi' => $data['jenis_sanksi'],
                            ];
                        }

                        $data['user_id'] = Auth::id();
                        return $data;
                    })
                    ->after(function (Model $record) {
                        /** @var \App\Models\Pengaduan $pengaduan */
                        $pengaduan = $this->getOwnerRecord();
                        $pengaduan->update(['status_pengaduan' => 'Tindak Lanjut Kesimpulan dan Rekomendasi']);
                        $rektors = User::role('rektor')->get();
                        if ($rektors->isNotEmpty()) {
                            Notification::send($rektors, new RekomendasiBaruNotification($record));
                        }
                    })
                    ->successNotification(
                        FilamentNotification::make()
                            ->success()
                            ->title('Surat Rekomendasi Berhasil Dibuat')
                            ->body('Status pengaduan telah diperbarui menjadi Tindak Lanjut Kesimpulan dan Rekomendasi.')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Surat Rekomendasi')
                    ->fillForm(fn(SuratRekomendasi $record): array => $this->fillInitialData($record)),

                Tables\Actions\Action::make('export_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document')
                    ->color('success')
                    ->url(fn($record) => route('surat_rekomendasi.export-pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->authorize(true)
                    ->visible(fn(SuratRekomendasi $record): bool => $record->status_rektor === 'Menunggu Persetujuan'),
            ]);
    }

    protected function fillInitialData(SuratRekomendasi $record): array
    {
        $formData = $record->toArray();
        $lhp = $record->laporanHasilPemeriksaan;
        $pengaduan = $this->getOwnerRecord();

        if ($lhp) {
            $formData['status_terbukti'] = $lhp->status_terbukti;
            $formData['jenis_kekerasan'] = $pengaduan->jenis_kejadian;

            if ($lhp->pasal_pelanggaran_id) {
                $pasalRecords = PasalPelanggaran::whereIn('id', $lhp->pasal_pelanggaran_id)->get();
                $pasalText = $pasalRecords->map(function ($pasal) {
                    $text = "Pasal {$pasal->pasal}";
                    if ($pasal->ayat !== '(-)') $text .= " Ayat {$pasal->ayat}";
                    if ($pasal->butir !== '-') $text .= " Butir {$pasal->butir}";
                    $text .= ": {$pasal->keterangan}";
                    return $text;
                })->implode("\n");
                $formData['pasal_pelanggaran_display'] = $pasalText;
            } else {
                $formData['pasal_pelanggaran_display'] = null;
            }
        }

        if ($record->status_terbukti === 'terbukti' && !empty($record->rekomendasi_data)) {
            $rekomendasi = $record->rekomendasi_data;
            $formData['status_pelaku_manual'] = $rekomendasi['status_pelaku_manual'] ?? null;
            $formData['jenis_sanksi'] = $rekomendasi['jenis_sanksi'] ?? null;
            $formData['sanksi_administratif'] = $rekomendasi['sanksi_administratif'] ?? null;
        }

        if (!empty($formData['pihak_direkomendasi_data'])) {
            $rebuiltPihakDirekomendasi = [];
            foreach ($formData['pihak_direkomendasi_data'] as $item) {
                $item['key'] = ($item['pihak_type'] ?? 'terlapor') . '|' . ($item['pihak_id'] ?? '');
                $rebuiltPihakDirekomendasi[] = $item;
            }
            $formData['pihak_direkomendasi_data'] = $rebuiltPihakDirekomendasi;
        }

        if (!empty($formData['pihak_pelapor_data'])) {
            $rebuiltPihakPelapor = [];
            foreach ($formData['pihak_pelapor_data'] as $item) {
                $item['key'] = ($item['pihak_type'] ?? '') . '|' . ($item['pihak_id'] ?? '');
                $rebuiltPihakPelapor[] = $item;
            }
            $formData['pihak_pelapor_data'] = $rebuiltPihakPelapor;
        }

        $formData['sanksi_ids'] = $record->sanksis()->pluck('pasal_sanksi_id')->all();

        return $formData;
    }
}
