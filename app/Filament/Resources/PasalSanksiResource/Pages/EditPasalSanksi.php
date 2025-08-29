<?php

namespace App\Filament\Resources\PasalSanksiResource\Pages;

use App\Filament\Resources\PasalSanksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPasalSanksi extends EditRecord
{
    protected static string $resource = PasalSanksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
