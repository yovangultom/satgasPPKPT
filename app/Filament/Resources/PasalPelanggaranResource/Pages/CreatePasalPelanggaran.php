<?php

namespace App\Filament\Resources\PasalPelanggaranResource\Pages;

use App\Filament\Resources\PasalPelanggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;


class CreatePasalPelanggaran extends CreateRecord
{
    protected static string $resource = PasalPelanggaranResource::class;
    protected static ?string $title = 'Tambah Pasal Pelanggaran';
    protected static ?string $breadcrumb = 'Tambah';


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
