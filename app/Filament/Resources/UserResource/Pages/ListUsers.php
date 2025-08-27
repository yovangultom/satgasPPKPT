<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
    protected static ?string $title = 'Akun';
    protected static ?string $breadcrumb = 'Daftar Akun';

    protected function getHeaderActions(): array
    {
        return [
            // Kita definisikan ulang CreateAction di sini
            Actions\CreateAction::make()
                ->createAnother(false)
                ->label('Tambah Akun')
                ->icon('heroicon-o-user-plus')
                ->color('success'),
        ];
    }
}
