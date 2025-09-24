<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Spatie\Permission\Models\Role;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Akun';
    protected static ?string $pluralModelLabel = 'Akun';
    protected static ?string $navigationGroup = 'Manajemen Akun';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create'),

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Role')
                    ->multiple(false)
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $roleName = Role::find($state)?->name;
                        if (in_array($roleName, ['rektor', 'htl', 'penanggung jawab'])) {
                            $set('status', 'dosen');
                        }
                    })
                    ->options(function () {
                        if (Auth::user()->hasRole('admin')) {
                            return Role::whereIn('name', ['petugas', 'rektor', 'htl', 'penanggung jawab'])->pluck('name', 'id');
                        }
                        return [];
                    }),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'mahasiswa' => 'Mahasiswa',
                        'dosen' => 'Dosen atau Tendik',
                    ])
                    ->required()
                    ->live()
                    ->visible(function (Get $get): bool {
                        $roleId = $get('roles');
                        if (is_array($roleId)) {
                            $roleId = $roleId[0] ?? null;
                        }
                        if (!$roleId) return true;

                        $roleName = Role::find($roleId)?->name;
                        return !in_array($roleName, ['rektor', 'htl', 'penanggung jawab']);
                    }),

                Forms\Components\TextInput::make('nim')
                    ->label('NIM')
                    ->requiredIf('status', 'mahasiswa')
                    ->unique(ignoreRecord: true)
                    ->visible(fn(Get $get): bool => $get('status') === 'mahasiswa'),

                Forms\Components\TextInput::make('nip')
                    ->label('NIP')
                    ->requiredIf('status', 'dosen')
                    ->unique(ignoreRecord: true)
                    ->visible(fn(Get $get): bool => $get('status') === 'dosen'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Akun')
                    ->modalDescription('Apakah Anda yakin ingin melakukan ini? Data tidak dapat dikembalikan.')
                    ->modalSubmitActionLabel('Ya, Hapus Sekarang')
                    ->modalCancelActionLabel('Batal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Akun')
                        ->modalDescription('Apakah Anda yakin ingin melakukan ini? Data tidak dapat dikembalikan.')
                        ->modalSubmitActionLabel('Ya, Hapus Sekarang')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('roles', function (Builder $query) {
            $query->whereIn('name', ['admin', 'petugas', 'rektor', 'htl', 'penanggung jawab']);
        });
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasRole('admin');
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->hasRole('admin') && Auth::user()->id !== $record->id;
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->hasRole('admin') && Auth::user()->id !== $record->id;
    }

    public static function canForceDelete(Model $record): bool
    {
        return Auth::user()->hasRole('admin') && Auth::user()->id !== $record->id;
    }
}
