<?php

namespace App\Filament\Resources\PengaduanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Pengaduan;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\BorangPenanganan;

class BorangPenanganansRelationManager extends RelationManager
{
    protected static string $relationship = 'borangPenanganans';
    protected static ?string $title = 'Borang Penanganan';
    public function canDelete(Model $record): bool
    {
        return Auth::user()->hasRole('admin');
    }
    public function canDeleteAny(): bool
    {
        return Auth::user()->hasRole('admin');
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Informasi Laporan Awal')
                    ->schema([
                        TextInput::make('nomor_pengaduan'),
                        TextInput::make('peran_pelapor'),
                        TextInput::make('nama_pelapor'),
                    ])->columns(3)->disabled(),

                Fieldset::make('Data Korban')
                    ->schema([
                        Repeater::make('korbans_display')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('nama'),
                                TextInput::make('jenis_kelamin'),
                                TextInput::make('status'),
                                TextInput::make('memiliki_disabilitas'),
                            ])
                            ->columnSpanFull()
                            ->addable(false)
                            ->deletable(false),
                    ])->disabled(),

                Fieldset::make('Data Terlapor')
                    ->schema([
                        Repeater::make('terlapors_display')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('nama'),
                                TextInput::make('jenis_kelamin'),
                                TextInput::make('status'),
                                TextInput::make('memiliki_disabilitas'),
                            ])
                            ->columnSpanFull()
                            ->addable(false)
                            ->deletable(false),
                    ])->disabled(),

                Fieldset::make('Isi Borang Penanganan')
                    ->schema([
                        Textarea::make('deskripsi_pengaduan')
                            ->label('Kronologi Peristiwa (Bisa Diedit)')
                            ->helperText('Anda dapat mengedit atau menambahkan catatan pada teks ini.')
                            ->rows(6)
                            ->columnSpanFull()
                            ->required(),

                        Repeater::make('pihak_yang_dihubungi')
                            ->schema([
                                TextInput::make('nama')->label('Pihak yang telah dihubungi')->required(),
                            ])
                            ->columnSpanFull()
                            ->label('Pihak yang telah dihubungi'),

                        TextInput::make('kerja_sama')
                            ->label('Kemungkinan Kerja Sama Satgas PPKS Itera dengan Pihak Lain')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle('Borang Penanganan')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID Borang')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Diisi oleh'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime()->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Borang Penanganan')
                    ->modalHeading(' Borang Penanganan')
                    ->createAnother(false)
                    ->modalSubmitActionLabel('Save')
                    ->authorize(true)
                    ->closeModalByClickingAway(false)
                    ->mountUsing(function (Form $form) {
                        /** @var Pengaduan $pengaduan */
                        $pengaduan = $this->ownerRecord;
                        $form->fill([
                            'nomor_pengaduan' => $pengaduan->nomor_pengaduan,
                            'peran_pelapor' => ucfirst($pengaduan->pelapors->first()?->pivot->peran_dalam_pengaduan ?? 'N/A'),
                            'nama_pelapor' => $pengaduan->pelapors->pluck('nama')->implode(', '),
                            'korbans_display' => $pengaduan->korbans->map(fn($k) => [
                                'nama' => $k->nama,
                                'jenis_kelamin' => $k->jenis_kelamin,
                                'status' => $k->status,
                                'memiliki_disabilitas' => $k->memiliki_disabilitas ? 'Ya' : 'Tidak',
                            ])->toArray(),
                            'terlapors_display' => $pengaduan->terlapors->map(fn($t) => [
                                'nama' => $t->nama,
                                'jenis_kelamin' => $t->jenis_kelamin,
                                'status' => $t->status,
                                'memiliki_disabilitas' => $t->memiliki_disabilitas ? 'Ya' : 'Tidak',
                            ])->toArray(),
                            'deskripsi_pengaduan' => $pengaduan->deskripsi_pengaduan,
                        ]);
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = Auth::id();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Borang Penanganan')
                    ->fillForm(fn(BorangPenanganan $record) => $this->fillReadOnlyData($record)),
                Tables\Actions\Action::make('export_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document')
                    ->color('success')
                    ->url(fn($record) => route('borang.export.penanganan', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->authorize(true),
            ])
            ->bulkActions([
                //
            ]);
    }
    protected function fillReadOnlyData(BorangPenanganan $record): array
    {
        /** @var Pengaduan $pengaduan */
        $pengaduan = $record->pengaduan;
        $formData = $record->toArray();

        $formData['nomor_pengaduan'] = $pengaduan->nomor_pengaduan;
        $formData['peran_pelapor'] = ucfirst($pengaduan->pelapors->first()?->pivot->peran_dalam_pengaduan ?? 'N/A');
        $formData['nama_pelapor'] = $pengaduan->pelapors->pluck('nama')->implode(', ');
        $formData['korbans_display'] = $pengaduan->korbans->map(fn($k) => ['nama' => $k->nama, 'jenis_kelamin' => $k->jenis_kelamin, 'status' => $k->status, 'memiliki_disabilitas' => $k->memiliki_disabilitas ? 'Ya' : 'Tidak'])->toArray();
        $formData['terlapors_display'] = $pengaduan->terlapors->map(fn($t) => ['nama' => $t->nama, 'jenis_kelamin' => $t->jenis_kelamin, 'status' => $t->status, 'memiliki_disabilitas' => $t->memiliki_disabilitas ? 'Ya' : 'Tidak'])->toArray();

        return $formData;
    }
}
