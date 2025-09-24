<?php

namespace App\Filament\Resources\PersetujuanPJResource\Pages;

use App\Filament\Resources\PersetujuanPJResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;


class ListPersetujuanPJS extends ListRecords
{
    protected static string $resource = PersetujuanPJResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
    public function getTabs(): array
    {
        return [
            'Menunggu Persetujuan' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_penanggung_jawab', 'Menunggu Persetujuan'))
                ->badge(static::getResource()::getModel()::where('status_penanggung_jawab', 'Menunggu Persetujuan')->count())
                ->badgeColor('warning'),
            'Sudah Diproses' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('status_penanggung_jawab', ['Disetujui', 'Ditolak'])),
            'Semua' => Tab::make(),
        ];
    }
}
