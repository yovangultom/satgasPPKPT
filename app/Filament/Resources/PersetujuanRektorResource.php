<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersetujuanRektorResource\Pages;
use App\Models\SuratRekomendasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\SelectFilter; // <-- Import SelectFilter

class PersetujuanRektorResource extends Resource
{
    protected static ?string $model = SuratRekomendasi::class;

    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $navigationLabel = 'Persetujuan Surat';
    protected static ?string $modelLabel = 'Persetujuan Surat Rekomendasi';
    protected static ?string $pluralModelLabel = 'Persetujuan Surat Rekomendasi';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('rektor');
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
                    ->label('Nomor Surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pengaduan.jenis_kejadian')
                    ->label('Jenis Kejadian'),

                Tables\Columns\TextColumn::make('status_rektor')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Menunggu Persetujuan' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_rektor')
                    ->label('Status Persetujuan')
                    ->options([
                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                        'Sudah Diproses' => 'Sudah Diproses',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['value'] === 'Sudah Diproses') {
                            return $query->whereIn('status_rektor', ['Disetujui', 'Ditolak']);
                        }
                        if ($data['value'] === 'Menunggu Persetujuan') {
                            return $query->where('status_rektor', 'Menunggu Persetujuan');
                        }
                        return $query;
                    })
                    ->default('Menunggu Persetujuan'),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Tinjau & Beri Respon')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->url(fn(SuratRekomendasi $record): string => static::getUrl('review', ['record' => $record]))
                    ->visible(fn(SuratRekomendasi $record): bool => $record->status_rektor === 'Menunggu Persetujuan'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPersetujuanRektors::route('/'),
            'review' => Pages\ReviewPersetujuan::route('/{record}/review'),
        ];
    }
}
