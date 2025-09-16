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
use Filament\Actions\Action;
use App\Notifications\PengaduanStatusUpdated;
use Illuminate\Support\Facades\Log;

class BeritaAcaraPemeriksaansRelationManager extends RelationManager
{
    protected static string $relationship = 'beritaAcaraPemeriksaans';
    protected static ?string $title = 'Berita Acara Pemeriksaan';
    protected static ?string $recordTitleAttribute = 'pihak_diperiksa_nama';

    public function form(Form $form): Form
    {
        return $form->schema($this->getFormSchema());
    }

    protected function getFormSchema(): array
    {
        $pengaduan = $this->getOwnerRecord();
        $pihakOptions = collect()
            ->merge($pengaduan->pelapors->mapWithKeys(fn($p) => ['pelapor|' . $p->id => "{$p->nama} (Pelapor)"]))
            ->merge($pengaduan->korbans->mapWithKeys(fn($k) => ['korban|' . $k->id => "{$k->nama} (Korban)"]))
            ->merge($pengaduan->terlapors->mapWithKeys(fn($t) => ['terlapor|' . $t->id => "{$t->nama} (Terlapor)"]))
            ->unique();
        $petugasOptions = User::whereHas('roles', fn($query) => $query->where('name', 'petugas'))->pluck('name', 'id');
        $jenisKejadianOptions = [
            'Kekerasan fisik' => 'Kekerasan fisik',
            'Kekerasan psikis' => 'Kekerasan psikis',
            'Perundungan' => 'Perundungan',
            'Kekerasan Seksual' => 'Kekerasan Seksual',
            'Kebijakan yang mengandung kekerasan' => 'Kebijakan yang mengandung kekerasan',
            'Diskriminasi dan Intoleransi' => 'Diskriminasi dan intoleransi',
        ];

        return [
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
                        ->hidden(fn(string $operation): bool => $operation !== 'create'),

                    Forms\Components\TextInput::make('pihak_diperiksa_nama')->label('Nama')->disabled()->dehydrated(false),
                    Forms\Components\TextInput::make('pihak_diperiksa_peran')->label('Peran')->disabled()->dehydrated(false),
                    Forms\Components\TextInput::make('pihak_diperiksa_jenis_kelamin')->label('Jenis Kelamin')->disabled()->dehydrated(false),

                    Forms\Components\Select::make('jenis_kejadian_awal')
                        ->label('Jenis Kejadian')
                        ->options($jenisKejadianOptions)
                        ->multiple()
                        ->required(),

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
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pihak_diperiksa_nama')
            ->columns([
                Tables\Columns\TextColumn::make('pihak_diperiksa_nama')->label('Pihak Diperiksa'),
                Tables\Columns\TextColumn::make('pihak_diperiksa_peran')->label('Peran'),
                Tables\Columns\TextColumn::make('jenis_kejadian_awal')
                    ->label('Jenis Kejadian')
                    ->badge(),
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
                    ->icon('heroicon-o-document')
                    ->form($this->getFormSchema())
                    ->createAnother(false)
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->authorize(true)
                    ->closeModalByClickingAway(false)
                    ->mountUsing(function (Form $form) {
                        $pengaduan = $this->getOwnerRecord();
                        $form->fill([
                            'jenis_kejadian_awal' => [$pengaduan->jenis_kejadian],
                            'uraian_singkat_kejadian' => $pengaduan->deskripsi_pengaduan

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
                    ->after(function (Model $record) {
                        /** @var \App\Models\Pengaduan $pengaduan */
                        $pengaduan = $this->getOwnerRecord();
                        $pengaduan->update(['status_pengaduan' => 'Investigasi']);

                        $pengaduan->refresh();

                        $user = $pengaduan->user;
                        if ($user) {
                            Log::info("Mempersiapkan notifikasi perubahan status (Investigasi) untuk User ID: {$user->id}, Email: {$user->email}");
                            $user->notify(new PengaduanStatusUpdated($pengaduan));
                            Log::info("Notifikasi untuk User ID: {$user->id} berhasil di-dispatch ke queue.");
                        } else {
                            Log::warning("Gagal mengirim notifikasi status (Investigasi): User tidak ditemukan untuk Pengaduan ID: {$pengaduan->id}");
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('BAP Berhasil Dibuat')
                            ->body('Status pengaduan telah diperbarui menjadi "Investigasi" dan notifikasi telah dikirim ke pengguna.')
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->modalHeading('Berita Acara Pemeriksaan')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading('Berita Acara Pemeriksaan')
                    ->form($this->getFormSchema())
                    ->mountUsing(function (Form $form, Model $record) {
                        $formData = $record->toArray();
                        $anggotaHadir = $record->anggota_satgas_ids;
                        if (is_array($anggotaHadir)) {
                            $formData['anggota_satgas_ids'] = collect($anggotaHadir)->pluck('id')->filter()->all();
                        }
                        $form->fill($formData);
                    })
                    ->disabledForm()
                    ->modalCancelActionLabel('Tutup')
                    ->modalSubmitAction(false)
                    ->closeModalByClickingAway(false),

                Tables\Actions\Action::make('export_pdf')
                    ->label('PDF')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn(Model $record) => route('bap.export.pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Berita Acara Pemeriksaan')
                    ->modalDescription('Apakah Anda yakin ingin melakukan ini? Data tidak dapat dikembalikan.')
                    ->modalSubmitActionLabel('Ya, Hapus Sekarang')
                    ->modalCancelActionLabel('Batal')
                    ->authorize(true),
            ])
            ->bulkActions([
                // 
            ]);
    }
}
