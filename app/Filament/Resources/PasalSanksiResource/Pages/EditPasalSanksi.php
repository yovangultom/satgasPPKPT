<?php

namespace App\Filament\Resources\PasalSanksiResource\Pages;

use App\Filament\Resources\PasalSanksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPasalSanksi extends EditRecord
{
    protected static string $resource = PasalSanksiResource::class;


    protected static ?string $breadcrumb = 'Ubah Data';
    protected static ?string $title = 'Ubah Pasal Sanksi';


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
