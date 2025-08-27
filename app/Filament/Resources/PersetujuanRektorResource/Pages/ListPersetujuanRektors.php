<?php

namespace App\Filament\Resources\PersetujuanRektorResource\Pages;

use App\Filament\Resources\PersetujuanRektorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersetujuanRektors extends ListRecords
{
    protected static string $resource = PersetujuanRektorResource::class;

    protected function getHeaderActions(): array
    {
        // Rektor tidak bisa membuat data baru, jadi kita kosongkan array ini.
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
