<?php

namespace App\Filament\Resources\PasalSanksiResource\Pages;

use App\Filament\Resources\PasalSanksiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePasalSanksi extends CreateRecord
{
    protected static string $resource = PasalSanksiResource::class;
    protected static ?string $title = 'Tambah Pasal Sanksi';

    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()->label('Simpan');
    }

    protected function getCancelFormAction(): Actions\Action
    {
        return parent::getCancelFormAction()->label('Batal');
    }


    public static function canCreateAnother(): bool
    {
        return false;
    }
}
