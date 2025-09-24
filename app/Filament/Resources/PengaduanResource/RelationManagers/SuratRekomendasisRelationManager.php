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
use Filament\Notifications\Actions\Action as NotificationAction;
use Illuminate\Support\Facades\Notification;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Log;
use Filament\Tables\Actions\Action;


class SuratRekomendasisRelationManager extends RelationManager
{
    protected static string $relationship = 'suratRekomendasis';
    protected static ?string $title = 'Surat Rekomendasi';

    public function canView(Model $record): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'petugas', 'penanggung jawab']);
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
            ->columns(1)
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
                    ->afterStateUpdated(function (Set $set, $state) {
                        if (blank($state)) {
                            $set('status_terbukti', null);
                            $set('jenis_kekerasan_display', null);
                            $set('pasal_pelanggaran_display', null);
                            return;
                        };
                        $lhp = LaporanHasilPemeriksaan::find($state);
                        if ($lhp) {
                            $set('status_terbukti', $lhp->status_terbukti);

                            if (!empty($lhp->pelanggaran_data)) {
                                $jenisKekerasanText = collect($lhp->pelanggaran_data)->pluck('jenis_kejadian')->unique()->implode(', ');
                                $set('jenis_kekerasan_display', $jenisKekerasanText);

                                $pasalIds = collect($lhp->pelanggaran_data)->pluck('pasal_pelanggaran_ids')->flatten()->unique()->filter();
                                $pasalRecords = PasalPelanggaran::whereIn('id', $pasalIds)->get();

                                $pasalText = $pasalRecords->map(function ($pasal) {
                                    $text = "Pasal {$pasal->pasal}";
                                    if ($pasal->ayat !== '(-)') $text .= " Ayat {$pasal->ayat}";
                                    if ($pasal->butir !== '-') $text .= " Butir {$pasal->butir}";
                                    $text .= ": {$pasal->keterangan}";
                                    return $text;
                                })->implode("\n");

                                $set('pasal_pelanggaran_display', $pasalText);
                            }
                        }
                    }),

                Forms\Components\Hidden::make('status_terbukti'),

                Forms\Components\Group::make()
                    ->schema([
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

                                            $suratPanggilan = $pengaduan->suratPanggilans()
                                                ->where('nama_pihak', $pihak->nama)
                                                ->first();

                                            if ($suratPanggilan) {
                                                $set('nim', $suratPanggilan->nim);
                                                $set('semester', $suratPanggilan->semester);
                                                $set('prodi', $suratPanggilan->program_studi);
                                                $set('fakultas', $suratPanggilan->fakultas);
                                                $set('nip_nrk', $suratPanggilan->nip);
                                                $set('fakultas_unit', $suratPanggilan->fakultas);
                                                $set('instansi', $suratPanggilan->asal_instansi);
                                            }
                                        }
                                    }),

                                Forms\Components\TextInput::make('nama')->label('Nama')->readOnly(),
                                Forms\Components\TextInput::make('status')->label('Status')->readOnly(),

                                Forms\Components\TextInput::make('nim')->label('NIM')->required()->visible(fn(Get $get) => $get('status') === 'Mahasiswa')->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('semester')->label('Semester')->required()->visible(fn(Get $get) => $get('status') === 'Mahasiswa')->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('prodi')->label('Program Studi')->required()->visible(fn(Get $get) => $get('status') === 'Mahasiswa')->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('fakultas')->label('Fakultas')->required()->visible(fn(Get $get) => $get('status') === 'Mahasiswa')->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('nip_nrk')->label('NIP/NRK')->required()->visible(fn(Get $get) => in_array($get('status'), ['Dosen', 'Tendik']))->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('fakultas_unit')->label('Fakultas/Lembaga/Unit')->required()->visible(fn(Get $get) => in_array($get('status'), ['Dosen', 'Tendik']))->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('nik')->label('NIK')->required()->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum'])),
                                Forms\Components\TextInput::make('instansi')->label('Instansi')->required()->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum']))->helperText('Data diisi otomatis jika tersedia.'),
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

                                            $suratPanggilan = $pengaduan->suratPanggilans()
                                                ->where('nama_pihak', $pihak->nama)
                                                ->first();

                                            if ($suratPanggilan) {
                                                $set('nim', $suratPanggilan->nim);
                                                $set('semester', $suratPanggilan->semester);
                                                $set('prodi', $suratPanggilan->program_studi);
                                                $set('fakultas', $suratPanggilan->fakultas);
                                                $set('nip_nrk', $suratPanggilan->nip);
                                                $set('fakultas_unit', $suratPanggilan->fakultas);
                                                $set('instansi', $suratPanggilan->asal_instansi);
                                            }
                                        }
                                    }),

                                Forms\Components\TextInput::make('nama')->label('Nama')->readOnly(),
                                Forms\Components\TextInput::make('status')->label('Status')->readOnly(),

                                Forms\Components\TextInput::make('nim')->label('NIM')->required()->visible(fn(Get $get) => $get('status') === 'Mahasiswa')->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('semester')->label('Semester')->required()->visible(fn(Get $get) => $get('status') === 'Mahasiswa')->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('prodi')->label('Program Studi')->required()->visible(fn(Get $get) => $get('status') === 'Mahasiswa')->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('fakultas')->label('Fakultas')->required()->visible(fn(Get $get) => $get('status') === 'Mahasiswa')->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('nip_nrk')->label('NIP/NRK')->required()->visible(fn(Get $get) => in_array($get('status'), ['Dosen', 'Tendik']))->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('fakultas_unit')->label('Fakultas/Lembaga/Unit')->required()->visible(fn(Get $get) => in_array($get('status'), ['Dosen', 'Tendik']))->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('nik')->label('NIK')->required()->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum'])),
                                Forms\Components\TextInput::make('instansi')->label('Instansi')->required()->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum']))->helperText('Data diisi otomatis jika tersedia.'),
                                Forms\Components\TextInput::make('keterangan')->label('Keterangan')->required()->visible(fn(Get $get) => in_array($get('status'), ['Warga Kampus', 'Masyarakat Umum'])),
                            ])
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Pihak Pelapor/Korban')
                            ->collapsible(),

                        Forms\Components\Section::make('Rekomendasi Sanksi')
                            ->visible(fn(Get $get) => $get('status_terbukti') === 'terbukti')
                            ->schema([
                                Placeholder::make('jenis_kekerasan_display')
                                    ->label('Jenis Kekerasan Sesuai LHP')
                                    ->content(function (Get $get): string {
                                        return nl2br(e($get('jenis_kekerasan_display') ?? 'Pilih LHP terlebih dahulu.'));
                                    }),

                                Placeholder::make('pasal_pelanggaran_display')
                                    ->label('Pasal Pelanggaran Sesuai LHP')
                                    ->content(function (Get $get): string {
                                        return nl2br(e($get('pasal_pelanggaran_display') ?? 'Pilih LHP terlebih dahulu.'));
                                    }),

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
                    ])
                    ->visible(fn(Get $get): bool => filled($get('laporan_hasil_pemeriksaan_id'))),

                Placeholder::make('Pilih LHP')
                    ->content('Silakan pilih Laporan Hasil Pemeriksaan (LHP) terlebih dahulu untuk melanjutkan pengisian.')
                    ->visible(fn(Get $get): bool => blank($get('laporan_hasil_pemeriksaan_id'))),

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
                Tables\Columns\TextColumn::make('status_penanggung_jawab')
                    ->label('Status Persetujuan PJ')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Menunggu Persetujuan' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('komentar_penanggung_jawab')
                    ->label('Komentar PJ')
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_rektor')
                    ->label('Status Persetujuan Rektor')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Menunggu Persetujuan' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        'Belum Diproses' => 'gray',
                        default => 'gray',
                    }),
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
                    ->visible(fn(): bool => auth()->user()->hasAnyRole(['admin', 'petugas']))
                    ->modalHeading('Surat Rekomendasi')
                    ->icon('heroicon-o-document')
                    ->createAnother(false)
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
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
                            $pasalIds = collect($lhp->pelanggaran_data)->pluck('pasal_pelanggaran_ids')->flatten()->unique()->filter();

                            $data['rekomendasi_data'] = [
                                'pasal_pelanggaran_ids' => $pasalIds->all(),
                                'sanksi_administratif' => $data['sanksi_administratif'],
                                'status_pelaku_manual' => $data['status_pelaku_manual'],
                                'jenis_sanksi' => $data['jenis_sanksi'],
                            ];
                        }

                        $data['user_id'] = Auth::id();
                        $data['status_penanggung_jawab'] = 'Menunggu Persetujuan';
                        $data['status_rektor'] = 'Belum Diproses';
                        return $data;
                    })
                    ->after(function (Model $record) {
                        /** @var \App\Models\Pengaduan $pengaduan */
                        $pengaduan = $this->getOwnerRecord();
                        $pengaduan->update(['status_pengaduan' => 'Tindak Lanjut Kesimpulan dan Rekomendasi']);

                        $penanggungJawabUsers = User::role('penanggung jawab')->get();
                        if ($penanggungJawabUsers->isNotEmpty()) {
                            FilamentNotification::make()
                                ->title('Persetujuan Surat Rekomendasi Baru')
                                ->body("Surat Rekomendasi baru untuk kasus No. {$record->nomor_surat} memerlukan persetujuan Anda.")
                                ->actions([
                                    NotificationAction::make('view')
                                        ->label('Lihat Detail')
                                        ->url(fn() => \App\Filament\Resources\PersetujuanPJResource::getUrl('index'))
                                ])
                                ->sendToDatabase($penanggungJawabUsers);
                        }

                        Log::info("Mempersiapkan untuk dispatch Job untuk SR ID: {$record->id}");
                        \App\Jobs\GenerateMergedRekomendasiPdf::dispatch($record);
                        Log::info("Job untuk SR ID: {$record->id} berhasil di-dispatch.");
                    })
                    ->successNotification(
                        FilamentNotification::make()
                            ->success()
                            ->title('Surat Rekomendasi Berhasil Dibuat')
                            ->body('Proses pembuatan PDF gabungan dan pengiriman notifikasi sedang berjalan di latar belakang.')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading('Surat Rekomendasi')
                    ->fillForm(fn(SuratRekomendasi $record): array => $this->fillInitialData($record))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->closeModalByClickingAway(false),
                Tables\Actions\Action::make('export_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn($record) => route('surat_rekomendasi.export-pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Surat Rekomendasi')
                    ->modalDescription('Apakah Anda yakin ingin melakukan ini? Data tidak dapat dikembalikan.')
                    ->modalSubmitActionLabel('Ya, Hapus Sekarang')
                    ->modalCancelActionLabel('Batal')
                    ->authorize(true)
                    ->visible(fn() => auth()->user()->hasRole('admin')),

            ]);
    }

    protected function fillInitialData(SuratRekomendasi $record): array
    {
        $formData = $record->toArray();
        $lhp = $record->laporanHasilPemeriksaan;
        $pengaduan = $this->getOwnerRecord();

        if ($lhp) {
            $formData['status_terbukti'] = $lhp->status_terbukti;

            if (!empty($lhp->pelanggaran_data)) {
                $formData['jenis_kekerasan_display'] = collect($lhp->pelanggaran_data)->pluck('jenis_kejadian')->unique()->implode(', ');

                $pasalIds = collect($lhp->pelanggaran_data)->pluck('pasal_pelanggaran_ids')->flatten()->unique()->filter();
                $pasalRecords = PasalPelanggaran::whereIn('id', $pasalIds)->get();
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
