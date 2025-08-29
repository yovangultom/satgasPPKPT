<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasalSanksiResource\Pages;
use App\Models\PasalSanksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PasalSanksiResource extends Resource
{
    protected static ?string $model = PasalSanksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationLabel = 'Pasal Sanksi';
    protected static ?string $modelLabel = 'Pasal Sanksi';
    protected static ?string $pluralModelLabel = 'Pasal Sanksi';
    protected static ?string $navigationGroup = 'Manajemen Pasal';
    protected static ?int $navigationSort = 2;

    /**
     * Hanya tampilkan menu ini untuk role 'admin'.
     */
    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jenis_sanksi')
                    ->label('Jenis Sanksi')
                    ->options([
                        'Ringan' => 'Ringan',
                        'Sedang' => 'Sedang',
                        'Berat' => 'Berat',
                    ])
                    ->required(),
                Forms\Components\Select::make('pelaku')
                    ->label('Status Pelaku')
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
                    ->required(),
                Forms\Components\TextInput::make('pasal')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ayat')
                    ->maxLength(255),
                Forms\Components\TextInput::make('butir')
                    ->maxLength(255),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan Sanksi')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_sanksi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pelaku')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pasal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ayat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('butir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->limit(50)
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPasalSanksis::route('/'),
            'create' => Pages\CreatePasalSanksi::route('/create'),
            'edit' => Pages\EditPasalSanksi::route('/{record}/edit'),
        ];
    }
}
