<?php

namespace App\Filament\Resources\PersetujuanRektorResource\Pages;

use App\Filament\Resources\PersetujuanRektorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePersetujuanRektors extends ManageRecords
{
    protected static string $resource = PersetujuanRektorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
