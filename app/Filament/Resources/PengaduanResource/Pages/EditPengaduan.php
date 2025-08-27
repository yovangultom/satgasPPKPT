<?php

namespace App\Filament\Resources\PengaduanResource\Pages;

use App\Filament\Resources\PengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengaduan extends EditRecord
{
    protected static string $resource = PengaduanResource::class;

    protected static ?string $title = 'Kelola Borang Pengaduan';
    protected static ?string $breadcrumb = 'Kelola Borang';
    protected function getHeaderActions(): array
    {
        return [];
    }
    public function getRelationManagers(): array
    {
        return [];
    }
}
