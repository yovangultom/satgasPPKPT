<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected static bool $canCreateAnother = false;
    protected static ?string $title = 'Akun Petugas';
    protected static ?string $breadcrumb = 'Akun Petugas';

    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()->label('Tambahkan');
    }
}
