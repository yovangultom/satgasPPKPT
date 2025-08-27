<?php

namespace App\Filament\Resources\PengaduanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Pengaduan;
use App\Models\User;
use App\Models\BorangPemeriksaan;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class BorangPemeriksaansRelationManager extends RelationManager
{
    protected static string $relationship = 'borangPemeriksaans';
    protected static ?string $title = 'Borang Pemeriksaan';

    public function canCreate(): bool
    {
        return true;
    }
    public function form(Form $form): Form
    {
        /** @var Pengaduan $pengaduan */
        $pengaduan = $this->ownerRecord;
        // BARU
        $petugasOptions = User::whereHas('roles', fn($query) => $query->where('name', 'petugas'))
            ->whereNotNull('name') // <-- TAMBAHKAN BARIS INI
            ->pluck('name', 'id');
        return $form
            ->schema([
                Fieldset::make('Informasi Laporan Awal')
                    ->schema([
                        TextInput::make('nomor_pengaduan_display')->label('Nomor Pengaduan')->default($pengaduan->nomor_pengaduan),
                        Repeater::make('terlapor_display')->label('Data Terlapor')
                            ->schema([
                                TextInput::make('nama'),
                                TextInput::make('memiliki_disabilitas'),
                            ])
                            ->default($pengaduan->terlapors->map(fn($t) => ['nama' => $t->nama, 'memiliki_disabilitas' => $t->memiliki_disabilitas ? 'Ya' : 'Tidak'])->toArray())
                            ->addable(false)->deletable(false)->columnSpanFull(),
                        Repeater::make('korban_display')->label('Data Korban (Jika pelapor adalah Saksi)')
                            ->schema([
                                TextInput::make('nama'),
                                TextInput::make('memiliki_disabilitas'),
                            ])
                            ->default($pengaduan->korbans->map(fn($k) => ['nama' => $k->nama, 'memiliki_disabilitas' => $k->memiliki_disabilitas ? 'Ya' : 'Tidak'])->toArray())
                            ->visible(fn() => $pengaduan->pelapors()->wherePivot('peran_dalam_pengaduan', 'Saksi')->exists())
                            ->addable(false)->deletable(false)->columnSpanFull(),
                    ])->disabled(),

                Fieldset::make('Detail Pemeriksaan')
                    ->schema([
                        Select::make('pemeriksa_info')
                            ->label('Petugas Pemeriksa')
                            ->options($petugasOptions)
                            ->multiple()
                            ->required()
                            ->columnSpanFull(),

                        DatePicker::make('tanggal_pemeriksaan')->native(false),
                        TextInput::make('tempat_pemeriksaan'),
                        Textarea::make('relasi_terlapor_korban')->label('Relasi akademik/profesional terlapor dengan korban')->columnSpanFull(),
                        Textarea::make('kronologi_pemeriksaan')->label('Kronologi kejadian (hasil pemeriksaan)')->columnSpanFull()->rows(5),
                        Textarea::make('kebutuhan_mendesak_verifikasi')->label('Kebutuhan mendesak korban (hasil verifikasi)')->columnSpanFull()->rows(5),
                        Textarea::make('pemeriksaan_bukti')->columnSpanFull()->rows(5),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle('Borang Pemeriksaan')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('user.name')->label('Dibuat oleh'),
                Tables\Columns\TextColumn::make('tanggal_pemeriksaan')->date(),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Input')->dateTime(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Borang Pemeriksaan')
                    ->modalHeading('Borang Pemeriksaan')
                    ->createAnother(false)
                    ->modalSubmitActionLabel('Save')
                    ->authorize(true)
                    ->closeModalByClickingAway(false)
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = Auth::id();

                        // --- LOGIKA YANG DIADOPSI DARI BeritaAcaraPemeriksaansRelationManager ---
                        $pemeriksaData = [];
                        if (!empty($data['pemeriksa_info'])) {
                            $petugasCollection = User::find($data['pemeriksa_info']);
                            foreach ($petugasCollection as $petugas) {
                                $pemeriksaData[] = [
                                    'id' => $petugas->id,
                                    'name' => $petugas->name,
                                    // Pastikan model User Anda memiliki kolom 'tanda_tangan'
                                    'tanda_tangan' => $petugas->tanda_tangan,
                                ];
                            }
                        }
                        // Mengganti array ID dengan array data petugas yang lebih lengkap
                        $data['pemeriksa_info'] = $pemeriksaData;
                        // --- AKHIR DARI LOGIKA ---

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Borang Pemeriksaan')
                    ->label('Lihat')
                    ->mountUsing(function (Form $form, Model $record) {
                        // Mengisi data untuk mode view/readonly
                        $data = $record->toArray();
                        $pengaduan = $record->pengaduan;

                        $data['nomor_pengaduan_display'] = $pengaduan->nomor_pengaduan;
                        $data['korban_display'] = $pengaduan->korbans->map(fn($k) => ['nama' => $k->nama, 'memiliki_disabilitas' => $k->memiliki_disabilitas ? 'Ya' : 'Tidak'])->toArray();
                        $data['terlapor_display'] = $pengaduan->terlapors->map(fn($t) => ['nama' => $t->nama, 'memiliki_disabilitas' => $t->memiliki_disabilitas ? 'Ya' : 'Tidak'])->toArray();

                        // Menyiapkan data petugas untuk ditampilkan di Select
                        if (is_array($record->pemeriksa_info)) {
                            $data['pemeriksa_info'] = collect($record->pemeriksa_info)->pluck('id')->all();
                        } else {
                            $data['pemeriksa_info'] = [];
                        }

                        $form->fill($data);
                    }),
                Tables\Actions\Action::make('export_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document')
                    ->color('success')
                    ->url(fn($record) => route('borang.export.pemeriksaan', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->authorize(true),
            ]);
    }
}
