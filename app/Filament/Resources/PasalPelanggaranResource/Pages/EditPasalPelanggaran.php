<?php

namespace App\Filament\Resources\PasalPelanggaranResource\Pages;

use App\Filament\Resources\PasalPelanggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPasalPelanggaran extends EditRecord
{
    protected static string $resource = PasalPelanggaranResource::class;
    protected static ?string $breadcrumb = 'Ubah Data';
    protected static ?string $title = 'Ubah Pasal Pelanggaran';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }
    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()->label('Simpan');
    }

    protected function getCancelFormAction(): Actions\Action
    {
        return parent::getCancelFormAction()->label('Batal');
    }
}
