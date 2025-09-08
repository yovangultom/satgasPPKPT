<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratKeputusanResource\Pages;
use App\Mail\SuratKeputusanTerkirimMail;
use App\Models\SuratRekomendasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class SuratKeputusanResource extends Resource
{
    protected static ?string $model = SuratRekomendasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?string $navigationLabel = 'Penerbitan SK';
    protected static ?string $modelLabel = 'Penerbitan Surat Keputusan';
    protected static ?string $pluralModelLabel = 'Penerbitan Surat Keputusan';

    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('htl');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status_rektor', 'Disetujui');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('Nomor Surat Rekomendasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pengaduan.jenis_kejadian')
                    ->label('Jenis Kejadian'),
                Tables\Columns\TextColumn::make('status_sk')
                    ->label('Status SK')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Menunggu Upload' => 'warning',
                        'Sudah Diunggah' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tanggal_respon_rektor')
                    ->label('Tanggal Disetujui')
                    ->dateTime('d M Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('lihat_rekomendasi')
                    ->label('Lihat')
                    ->modalHeading('Dokumen Gabungan Surat Rekomendasi')
                    ->icon('heroicon-o-eye')
                    ->modalContent(function (SuratRekomendasi $record): \Illuminate\Contracts\View\View {
                        $pdfUrl = $record->file_gabungan_path
                            ? Storage::disk('public')->url($record->file_gabungan_path)
                            : null;

                        return view(
                            'filament.resources.pdfviewer.pages.pdf-viewer',
                            ['pdfUrl' => $pdfUrl]
                        );
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->closeModalByClickingAway(false),

                Tables\Actions\Action::make('upload_dan_kirim_sk')
                    ->label('Upload & Kirim SK')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\FileUpload::make('file_sk_path')
                            ->label('File Surat Keputusan (PDF)')
                            ->required()
                            ->disk('public')
                            ->directory('surat-keputusan')
                            ->acceptedFileTypes(['application/pdf']),
                        Forms\Components\TagsInput::make('emails')
                            ->label('Email Penerima')
                            ->placeholder('Tambah email lalu tekan Enter')
                            ->helperText('Anda bisa menambahkan lebih dari satu email.')
                            ->required()
                            ->nestedRecursiveRules('email'),
                    ])
                    ->action(function (SuratRekomendasi $record, array $data) {
                        $record->update([
                            'file_sk_path' => $data['file_sk_path'],
                            'status_sk' => 'Sudah Diunggah',
                            'email_penerima_sk' => $data['emails'],

                        ]);

                        foreach ($data['emails'] as $email) {
                            Mail::to($email)->send(new SuratKeputusanTerkirimMail($record, $data['file_sk_path']));
                        }

                        Notification::make()
                            ->title('Upload & Kirim Email Berhasil')
                            ->body('File SK telah diunggah dan dikirim ke alamat email yang dituju.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(SuratRekomendasi $record): bool => $record->status_sk === 'Menunggu Upload'),

                Tables\Actions\Action::make('download_sk')
                    ->label('Lihat SK')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn(SuratRekomendasi $record) => Storage::disk('public')->url($record->file_sk_path))
                    ->openUrlInNewTab()
                    ->visible(fn(SuratRekomendasi $record): bool => !empty($record->file_sk_path)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratKeputusans::route('/'),
        ];
    }
}
