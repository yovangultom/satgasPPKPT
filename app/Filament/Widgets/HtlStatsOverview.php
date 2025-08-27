<?php

namespace App\Filament\Widgets;

use App\Models\SuratRekomendasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class HtlStatsOverview extends BaseWidget
{
    /**
     * Method ini akan menentukan apakah widget ini boleh ditampilkan atau tidak.
     */
    public static function canView(): bool
    {
        return Auth::user()->hasRole('htl');
    }

    protected function getStats(): array
    {
        $baseQuery = SuratRekomendasi::where('status_rektor', 'Disetujui');

        $totalSk = $baseQuery->count();
        $menungguUpload = $baseQuery->clone()->where('status_sk', 'Menunggu Upload')->count();
        $sudahDiunggah = $baseQuery->clone()->where('status_sk', 'Sudah Diunggah')->count();

        return [
            Stat::make('Total Penerbitan SK', $totalSk)
                ->description('Total surat yang perlu dibuatkan SK')
                ->color('primary'),
            Stat::make('Menunggu Penerbitan SK', $menungguUpload)
                ->description('SK yang belum diunggah')
                ->color('warning'),
            Stat::make('Penerbitan SK Berhasil', $sudahDiunggah)
                ->description('SK yang telah berhasil diunggah')
                ->color('success'),
        ];
    }
}
