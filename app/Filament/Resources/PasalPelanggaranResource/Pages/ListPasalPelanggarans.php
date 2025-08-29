<?php

namespace App\Filament\Resources\PasalPelanggaranResource\Pages;

use App\Filament\Resources\PasalPelanggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPasalPelanggarans extends ListRecords
{
    protected static string $resource = PasalPelanggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
