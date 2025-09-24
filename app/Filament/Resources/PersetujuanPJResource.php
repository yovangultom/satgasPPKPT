<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersetujuanPJResource\Pages;
use App\Models\SuratRekomendasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;




class PersetujuanPJResource extends Resource
{
    protected static ?string $model = SuratRekomendasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationLabel = 'Persetujuan PJ';
    protected static ?string $modelLabel = 'Persetujuan Surat Rekomendasi PJ';
    protected static ?string $pluralModelLabel = 'Persetujuan Surat Rekomendasi PJ ';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('penanggung jawab');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengaduan.nomor_pengaduan')
                    ->label('Nomor Kasus')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Dibuat oleh Petugas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_penanggung_jawab')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Menunggu Persetujuan' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Proses Persetujuan')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->url(fn($record): string => static::getUrl('review', ['record' => $record]))
                    ->visible(fn($record): bool => $record->status_penanggung_jawab === 'Menunggu Persetujuan'),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPersetujuanPJS::route('/'),
            'review' => Pages\ViewPersetujuanPJ::route('/{record}/review'),
        ];
    }
}
