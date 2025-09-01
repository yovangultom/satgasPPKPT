<?php

namespace App\Filament\Resources;

use Filament\Actions;
use App\Filament\Resources\PengaduanResource\Pages;
use App\Models\Pengaduan;
use App\Models\StatusHistory;
use App\Models\Terlapor;
use App\Models\Pelapor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\FileUpload;
use App\Notifications\PengaduanStatusUpdated;
use Filament\Notifications\Notification;
use App\Filament\Resources\PengaduanResource\RelationManagers;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use PhpParser\Node\Stmt\Label;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Manajemen Pengaduan';
    protected static ?string $modelLabel = 'Pengaduan';
    protected static ?string $pluralModelLabel = 'Pengaduan';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return !auth()->user()->hasAnyRole(['rektor', 'htl']);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Utama Pengaduan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('nomor_pengaduan')->label('Nomor Pengaduan'),
                        TextEntry::make('status_pengaduan')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Menunggu' => 'gray',
                                'Verifikasi' => 'warning',
                                'Investigasi' => 'info',
                                'Penyusunan Kesimpulan dan Rekomendasi' => 'primary',
                                'Tindak Lanjut Kesimpulan dan Rekomendasi' => 'primary',
                                'Selesai' => 'success',
                                default => 'gray',
                            }),
                        TextEntry::make('user.name')->label('Pelapor'),
                        TextEntry::make('tanggal_pelaporan')->dateTime('d F Y, H:i'),
                    ]),
                Section::make('Pelapor')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('pelapors.nama')->label('Nama Pelapor'),
                        TextEntry::make('pelapors.jenis_kelamin')->label('Jenis Kelamin'),
                        TextEntry::make('pelapors.nomor_telepon')->label('Nomor Telepon'),
                        TextEntry::make('peran_pelapor')
                            ->label('Berperan sebagai')
                            ->state(function ($record) {
                                $pelapor = $record->pelapors->first();
                                return $pelapor ? $pelapor->pivot->peran_dalam_pengaduan : 'N/A';
                            }),
                        TextEntry::make('pelapors.domisili')->label('Domisili'),
                        TextEntry::make('pelapors.status')->label('Status'),
                        TextEntry::make('pelapors.memiliki_disabilitas')
                            ->label('Memiliki disabiltas')
                            ->listWithLineBreaks()
                            ->formatStateUsing(fn($state): string => $state ? 'Ya' : 'Tidak'),
                    ]),
                Section::make('Terlapor')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('terlapors.nama')
                            ->label('Nama Terlapor')
                            ->formatStateUsing(function (Model $record): string {
                                $terlapors = $record->terlapors;
                                if ($terlapors->isEmpty()) {
                                    return '-';
                                }
                                if ($terlapors->count() === 1) {
                                    return $terlapors->first()->nama;
                                }
                                return $terlapors->map(function ($terlapor, $index) {
                                    return ($index + 1) . '. ' . $terlapor->nama;
                                })->implode('<br>');
                            })
                            ->html(),
                        TextEntry::make('terlapors.jenis_kelamin')->label('Jenis Kelamin')->listWithLineBreaks(),
                        TextEntry::make('terlapors.nomor_telepon')->label('Nomor Telepon')->listWithLineBreaks(),
                        TextEntry::make('terlapors.domisili')->label('Domisili')->listWithLineBreaks(),
                        TextEntry::make('terlapors.status')->label('Status')->listWithLineBreaks(),
                        TextEntry::make('terlapors.memiliki_disabilitas')
                            ->label('Memiliki disabiltas')
                            ->listWithLineBreaks()
                            ->formatStateUsing(fn($state): string => $state ? 'Ya' : 'Tidak'),
                    ]),
                Section::make('Korban')
                    ->columns(2)
                    ->visible(function (Model $record): bool {
                        return $record->pelapors()->wherePivot('peran_dalam_pengaduan', 'Saksi')->exists();
                    })
                    ->schema([
                        TextEntry::make('korbans.nama')
                            ->label('Nama Korban')
                            ->formatStateUsing(function (Model $record): string {
                                $korbans = $record->korbans;
                                if ($korbans->isEmpty()) {
                                    return '-';
                                }
                                if ($korbans->count() === 1) {
                                    return $korbans->first()->nama;
                                }
                                return $korbans->map(function ($korban, $index) {
                                    return ($index + 1) . '. ' . $korban->nama;
                                })->implode('<br>');
                            })
                            ->html(),
                        TextEntry::make('korbans.jenis_kelamin')->label('Jenis Kelamin')->listWithLineBreaks(),
                        TextEntry::make('korbans.nomor_telepon')->label('Nomor Telepon')->listWithLineBreaks(),
                        TextEntry::make('korbans.domisili')->label('Domisili')->listWithLineBreaks(),
                        TextEntry::make('korbans.status')->label('Status')->listWithLineBreaks(),
                        TextEntry::make('korbans.memiliki_disabilitas')
                            ->label('Memiliki Disabilitas')
                            ->listWithLineBreaks()
                            ->formatStateUsing(fn($state): string => $state ? 'Ya' : 'Tidak'),
                    ]),
                Section::make('Detail Kejadian')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('jenis_kejadian'),
                        TextEntry::make('tanggal_kejadian')->date('d F Y'),
                        TextEntry::make('lokasi_kejadian')->columnSpanFull(),
                        TextEntry::make('deskripsi_pengaduan')->columnSpanFull()->extraAttributes(['class' => 'text-justify']),
                    ]),
                Section::make('Alasan Melapor dan Kebutuhan Korban')
                    ->columns(1)
                    ->schema([
                        TextEntry::make('alasan_pengaduan')->listWithLineBreaks()->bulleted(),
                        TextEntry::make('identifikasi_kebutuhan_korban')->listWithLineBreaks()->bulleted(),
                    ]),
                Section::make('Lampiran dan Bukti')
                    ->schema([
                        TextEntry::make('bukti_pendukung')
                            ->label('File Bukti Pendukung (ZIP/RAR)')
                            ->color('primary')
                            ->url(fn($record): ?string => $record->bukti_pendukung ? Storage::disk('public')->url($record->bukti_pendukung) : null, true),
                        TextEntry::make('url_bukti_tambahan')->label('URL Bukti Tambahan')->url(fn($state) => $state)->openUrlInNewTab()->placeholder('Tidak ada URL.'),
                    ]),
                Section::make('Persetujuan dan Tanda Tangan')
                    ->schema([
                        ImageEntry::make('tanda_tangan_pelapor_image_url')->label('Tanda Tangan Pelapor'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pengaduan')->label('Nomor Pengaduan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jenis_kejadian')->label('Judul/Jenis Kejadian')->limit(40)->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pelaporan')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('status_pengaduan')->searchable()->badge()->color(fn(string $state): string => match ($state) {
                    'Menunggu' => 'gray',
                    'Verifikasi' => 'warning',
                    'Investigasi' => 'info',
                    'Penyusunan Kesimpulan dan Rekomendasi' => 'primary',
                    'Tindak Lanjut Kesimpulan dan Rekomendasi' => 'primary',
                    'Selesai' => 'success',
                    default => 'gray',
                }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('View'),
                TableAction::make('updateStatus')
                    ->label('Edit Status')
                    ->size(ActionSize::Large)
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->fillForm(fn(Pengaduan $record): array => ['status_pengaduan' => $record->status_pengaduan])
                    ->form(function (Pengaduan $record) {
                        $usedStatuses = $record->statusHistories()->pluck('status')->toArray();
                        $allStatuses = [
                            'Menunggu' => 'Menunggu',
                            'Verifikasi' => 'Verifikasi',
                            'Investigasi' => 'Investigasi',
                            'Penyusunan Kesimpulan dan Rekomendasi' => 'Penyusunan Kesimpulan dan Rekomendasi',
                            'Tindak Lanjut Kesimpulan dan Rekomendasi' => 'Tindak Lanjut Kesimpulan dan Rekomendasi',
                            'Selesai' => 'Selesai',
                        ];
                        return [
                            Forms\Components\Select::make('status_pengaduan')
                                ->label('Status Pengaduan')
                                ->options($allStatuses)
                                ->required()
                                ->native(false)
                                ->disableOptionWhen(function (string $value) use ($usedStatuses): bool {
                                    return in_array($value, $usedStatuses);
                                }),
                        ];
                    })
                    ->action(function (array $data, Pengaduan $record): void {
                        $record->update($data);
                        $record->statusHistories()->create([
                            'status' => $data['status_pengaduan'],
                        ]);
                        $record->refresh();

                        $user = $record->user;
                        if ($user) {
                            $user->notify(new PengaduanStatusUpdated($record));
                        }
                    })
                    ->successNotification(Notification::make()->success()->title('Status Diperbarui')->body('Status pengaduan telah berhasil diperbarui dan notifikasi telah dikirim ke pengguna.')),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengaduans::route('/'),
            'view' => Pages\ViewPengaduan::route('/{record}'),
        ];
    }
    public static function getRelations(): array
    {
        return [

            RelationManagers\MessagesRelationManager::class,
            RelationManagers\BorangPenanganansRelationManager::class,
            RelationManagers\BorangPemeriksaansRelationManager::class,

        ];
    }
}
