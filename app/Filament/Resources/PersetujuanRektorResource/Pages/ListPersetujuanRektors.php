<?php

namespace App\Filament\Resources\PersetujuanRektorResource\Pages;

use App\Filament\Resources\PersetujuanRektorResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;

class ListPersetujuanRektors extends ListRecords
{
    protected static string $resource = PersetujuanRektorResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'Menunggu Persetujuan' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_rektor', 'Menunggu Persetujuan'))
                ->badge(static::getResource()::getModel()::where('status_rektor', 'Menunggu Persetujuan')->count())
                ->badgeColor('warning'),
            'Sudah Diproses' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('status_rektor', ['Disetujui', 'Ditolak'])),
            'Semua' => Tab::make(),
        ];
    }
}
