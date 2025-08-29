<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasalPelanggaranResource\Pages;
use App\Models\PasalPelanggaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PasalPelanggaranResource extends Resource
{
    protected static ?string $model = PasalPelanggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Pasal Pelanggaran';
    protected static ?string $modelLabel = 'Pasal Pelanggaran';
    protected static ?string $pluralModelLabel = 'Pasal Pelanggaran';
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
                Forms\Components\Select::make('jenis_kekerasan')
                    ->label('Jenis Kekerasan')
                    ->options([
                        'Kekerasan Fisik' => 'Kekerasan Fisik',
                        'Kekerasan Psikis' => 'Kekerasan Psikis',
                        'Perundungan' => 'Perundungan',
                        'Kekerasan Seksual' => 'Kekerasan Seksual',
                        'Diskriminasi dan Intoleransi' => 'Diskriminasi dan Intoleransi',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('pasal')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ayat')
                    ->maxLength(255),
                Forms\Components\TextInput::make('butir')
                    ->maxLength(255),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan Pasal')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_kekerasan')
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
            'index' => Pages\ListPasalPelanggarans::route('/'),
            'create' => Pages\CreatePasalPelanggaran::route('/create'),
            'edit' => Pages\EditPasalPelanggaran::route('/{record}/edit'),
        ];
    }
}
