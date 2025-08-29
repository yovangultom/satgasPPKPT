<?php

namespace App\Filament\Resources\PengaduanResource\Pages;

use App\Filament\Resources\PengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;

class ListPengaduans extends ListRecords
{
    protected static string $resource = PengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Pengaduan'),

            'belum_dikerjakan' => Tab::make('Belum Dikerjakan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_pengaduan', 'Menunggu'))
                ->badge(PengaduanResource::getModel()::where('status_pengaduan', 'Menunggu')->count())
                ->badgeColor('gray'),

            'sedang_dikerjakan' => Tab::make('Sedang Dikerjakan')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('status_pengaduan', [
                    'Verifikasi',
                    'Investigasi',
                    'Penyusunan Kesimpulan dan Rekomendasi',
                    'Tindak Lanjut Kesimpulan dan Rekomendasi'
                ]))
                ->badge(PengaduanResource::getModel()::whereIn('status_pengaduan', [
                    'Verifikasi',
                    'Investigasi',
                    'Penyusunan Kesimpulan dan Rekomendasi',
                    'Tindak Lanjut Kesimpulan dan Rekomendasi'
                ])->count())
                ->badgeColor('info'),

            'selesai' => Tab::make('Selesai Dikerjakan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_pengaduan', 'Selesai'))
                ->badge(PengaduanResource::getModel()::where('status_pengaduan', 'Selesai')->count())
                ->badgeColor('success'),
        ];
    }
}
