<?php

namespace App\Filament\Resources\PengaduanResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengaduan;
use Filament\Notifications\Notification;



class BeritaAcaraPemeriksaansRelationManager extends RelationManager
{
    protected static string $relationship = 'beritaAcaraPemeriksaans';
    protected static ?string $title = 'Berita Acara Pemeriksaan';
    protected static ?string $recordTitleAttribute = 'pihak_diperiksa_nama';
    public function isReadOnly(): bool
    {
        return false;
    }
    public function form(Form $form): Form
    {
        $pengaduan = $this->getOwnerRecord();
        $pihakOptions = collect()
            ->merge($pengaduan->pelapors->mapWithKeys(fn($p) => ['pelapor|' . $p->id => "{$p->nama} (Pelapor)"]))
            ->merge($pengaduan->korbans->mapWithKeys(fn($k) => ['korban|' . $k->id => "{$k->nama} (Korban)"]))
            ->merge($pengaduan->terlapors->mapWithKeys(fn($t) => ['terlapor|' . $t->id => "{$t->nama} (Terlapor)"]))
            ->unique();
        $petugasOptions = User::whereHas('roles', fn($query) => $query->where('name', 'petugas'))->pluck('name', 'id');

        return $form
            ->schema([
                Forms\Components\Section::make('Data Pihak Diperiksa')
                    ->schema([
                        Forms\Components\Select::make('pihak_diperiksa_key')
                            ->label('Pilih Pihak yang Diperiksa')
                            ->options($pihakOptions)
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) use ($pengaduan) {
                                if (blank($state)) return;
                                [$tipe, $id] = explode('|', $state);
                                $pihak = match ($tipe) {
                                    'pelapor' => $pengaduan->pelapors()->find($id),
                                    'korban' => $pengaduan->korbans()->find($id),
                                    'terlapor' => $pengaduan->terlapors()->find($id),
                                };
                                if ($pihak) {
                                    $set('pihak_diperiksa_nama', $pihak->nama);

                                    $peran = $tipe;
                                    if ($tipe === 'pelapor') {
                                        $peran = $pihak->pivot->peran_dalam_pengaduan ?? 'Pelapor';
                                    }
                                    $set('pihak_diperiksa_peran', ucfirst($peran));

                                    $set('pihak_diperiksa_jenis_kelamin', $pihak->jenis_kelamin);
                                }
                            })
                            ->required()
                            ->hidden(fn(string $operation): bool => $operation === 'edit'),

                        Forms\Components\TextInput::make('pihak_diperiksa_nama')->label('Nama')->disabled(),
                        Forms\Components\TextInput::make('pihak_diperiksa_peran')->label('Peran')->disabled(),
                        Forms\Components\TextInput::make('pihak_diperiksa_jenis_kelamin')->label('Jenis Kelamin')->disabled(),
                        Forms\Components\TextInput::make('jenis_kejadian_awal')->label('Jenis Kejadian')->disabled(),
                        Forms\Components\TextInput::make('pihak_diperiksa_tempat_lahir')->label('Tempat Lahir')->required(),
                        Forms\Components\DatePicker::make('pihak_diperiksa_tanggal_lahir')->label('Tanggal Lahir')->required(),
                        Forms\Components\TextInput::make('pihak_diperiksa_agama')->label('Agama')->required(),
                        Forms\Components\Textarea::make('pihak_diperiksa_alamat')->label('Alamat')->required()->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Kejadian & Pemeriksaan')
                    ->schema([
                        Forms\Components\Textarea::make('uraian_singkat_kejadian')->label('Uraian Singkat Kejadian')->required()->rows(5)->columnSpanFull(),
                        Forms\Components\DatePicker::make('tanggal_kejadian')->label('Tanggal Kejadian')->required(),
                        Forms\Components\TextInput::make('tempat_kejadian')->label('Tempat Kejadian')->required(),
                        Forms\Components\TextInput::make('saksi_pendamping')->label('Saksi Pendamping (jika ada)'),
                        Forms\Components\DatePicker::make('tanggal_pemeriksaan')->label('Tanggal Pemeriksaan BAP')->required(),
                        Forms\Components\TimePicker::make('waktu_pemeriksaan')->label('Waktu Pemeriksaan BAP')->required()->seconds(false)->native(false)->displayFormat('H:i'),
                        Forms\Components\TextInput::make('tempat_pemeriksaan')->label('Tempat Pemeriksaan BAP')->required()->default('Ruang Satgas PPKPT Gedung D ITERA'),
                        Forms\Components\Select::make('anggota_satgas_ids')
                            ->label('Anggota Petugas Hadir')
                            ->options($petugasOptions)
                            ->multiple()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('tanda_tangan_terperiksa')
                            ->label('Upload Tanda Tangan Pihak Diperiksa')
                            ->image()
                            ->directory('tanda-tangan-bap')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pihak_diperiksa_nama')
            ->columns([
                Tables\Columns\TextColumn::make('pihak_diperiksa_nama')->label('Pihak Diperiksa'),
                Tables\Columns\TextColumn::make('pihak_diperiksa_peran')->label('Peran'),
                Tables\Columns\TextColumn::make('tanggal_pemeriksaan')->date('d F Y')->label('Tgl Pemeriksaan'),
                Tables\Columns\TextColumn::make('user.name')->label('Dibuat oleh'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime()->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Berita Acara Pemeriksaan')
                    ->modalHeading('Berita Acara Pemeriksaan')
                    ->createAnother(false)
                    ->modalSubmitActionLabel('Save')
                    ->authorize(true)
                    ->closeModalByClickingAway(false)
                    ->mountUsing(function (Form $form) {
                        $pengaduan = $this->getOwnerRecord();
                        $form->fill([
                            'jenis_kejadian_awal' => $pengaduan->jenis_kejadian
                        ]);
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $pengaduan = $this->getOwnerRecord();
                        [$tipe, $id] = explode('|', $data['pihak_diperiksa_key']);
                        $pihak = match ($tipe) {
                            'pelapor' => $pengaduan->pelapors()->find($id),
                            'korban' => $pengaduan->korbans()->find($id),
                            'terlapor' => $pengaduan->terlapors()->find($id),
                        };

                        $data['user_id'] = Auth::id();
                        $data['pihak_diperiksa_nama'] = $pihak->nama;
                        $peran = $tipe;
                        if ($tipe === 'pelapor') {
                            $peran = $pihak->pivot->peran_dalam_pengaduan ?? 'Pelapor';
                        }
                        $data['pihak_diperiksa_peran'] = ucfirst($peran);

                        $data['pihak_diperiksa_jenis_kelamin'] = $pihak->jenis_kelamin;
                        $data['jenis_kejadian_awal'] = $pengaduan->jenis_kejadian;

                        $petugasHadirData = [];
                        if (!empty($data['anggota_satgas_ids'])) {
                            $petugasCollection = User::find($data['anggota_satgas_ids']);
                            foreach ($petugasCollection as $petugas) {
                                $petugasHadirData[] = [
                                    'id' => $petugas->id,
                                    'name' => $petugas->name,
                                    'tanda_tangan' => $petugas->tanda_tangan,
                                ];
                            }
                        }
                        $data['anggota_satgas_ids'] = $petugasHadirData;

                        unset($data['pihak_diperiksa_key']);
                        return $data;
                    })
                    ->after(function () {
                        /** @var \App\Models\Pengaduan $pengaduan */
                        $pengaduan = $this->getOwnerRecord();
                        $pengaduan->update(['status_pengaduan' => 'Investigasi']);
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('BAP Berhasil Dibuat')
                            ->body('Status pengaduan telah diperbarui menjadi Investigasi.')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Berita Acara Pemeriksaan')
                    ->mountUsing(function (Form $form, Model $record) {
                        $this->fillFormWithRecordData($form, $record);
                    })
                    ->closeModalByClickingAway(false),
                Tables\Actions\Action::make('export_pdf')
                    ->label('PDF')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn(Model $record) => route('bap.export.pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                // 
            ]);
    }
    protected function fillFormWithRecordData(Form $form, Model $record): void
    {
        $data = [
            'pihak_diperiksa_nama' => $record->pihak_diperiksa_nama,
            'pihak_diperiksa_peran' => $record->pihak_diperiksa_peran,
            'pihak_diperiksa_jenis_kelamin' => $record->pihak_diperiksa_jenis_kelamin,
            'pihak_diperiksa_tempat_lahir' => $record->pihak_diperiksa_tempat_lahir,
            'pihak_diperiksa_tanggal_lahir' => $record->pihak_diperiksa_tanggal_lahir,
            'pihak_diperiksa_agama' => $record->pihak_diperiksa_agama,
            'pihak_diperiksa_alamat' => $record->pihak_diperiksa_alamat,
            'uraian_singkat_kejadian' => $record->uraian_singkat_kejadian,
            'tanggal_kejadian' => $record->tanggal_kejadian,
            'tempat_kejadian' => $record->tempat_kejadian,
            'saksi_pendamping' => $record->saksi_pendamping,
            'tanggal_pemeriksaan' => $record->tanggal_pemeriksaan,
            'waktu_pemeriksaan' => $record->waktu_pemeriksaan,
            'tempat_pemeriksaan' => $record->tempat_pemeriksaan,
            'tanda_tangan_terperiksa' => $record->tanda_tangan_terperiksa,

        ];

        $jenisKejadian = $record->jenis_kejadian_awal;
        if (is_array($jenisKejadian)) {
            $data['jenis_kejadian_awal'] = implode(', ', $jenisKejadian);
        } else {
            $data['jenis_kejadian_awal'] = (string) $jenisKejadian;
        }

        $anggotaHadir = $record->anggota_satgas_ids;
        if (is_array($anggotaHadir)) {
            $data['anggota_satgas_ids'] = collect($anggotaHadir)->pluck('id')->filter()->all();
        } else {
            $data['anggota_satgas_ids'] = [];
        }

        $form->fill($data);
    }
}
