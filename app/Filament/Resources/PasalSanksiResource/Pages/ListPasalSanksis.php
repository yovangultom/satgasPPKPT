<?php

namespace App\Filament\Resources\PasalSanksiResource\Pages;

use App\Filament\Resources\PasalSanksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPasalSanksis extends ListRecords
{
    protected static string $resource = PasalSanksiResource::class;
    protected static ?string $breadcrumb = 'List Data Pasal Sanksi';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Pasal Sanksi')->icon('heroicon-m-plus'),
        ];
    }
}
