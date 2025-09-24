<?php

namespace App\Filament\Resources\PersetujuanPJResource\Pages;

use App\Filament\Resources\PersetujuanPJResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersetujuanPJ extends EditRecord
{
    protected static string $resource = PersetujuanPJResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),

        ];
    }
}
