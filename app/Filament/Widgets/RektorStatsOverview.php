<?php

namespace App\Filament\Widgets;

use App\Models\SuratRekomendasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class RektorStatsOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return Auth::user()->hasRole('rektor');
    }

    protected function getStats(): array
    {
        $totalSurat = SuratRekomendasi::where('status_penanggung_jawab', 'Disetujui')->count();

        $menungguPersetujuan = SuratRekomendasi::where('status_penanggung_jawab', 'Disetujui')
            ->where('status_rektor', 'Menunggu Persetujuan')->count();

        $selesaiDitinjau = SuratRekomendasi::where('status_penanggung_jawab', 'Disetujui')
            ->whereIn('status_rektor', ['Disetujui', 'Ditolak'])->count();

        return [
            Stat::make('Total Surat Diajukan', $totalSurat)
                ->description('Total seluruh surat rekomendasi yang masuk')
                ->icon('heroicon-o-envelope'),
            Stat::make('Menunggu Persetujuan', $menungguPersetujuan)
                ->description('Surat yang memerlukan respon Anda')
                ->color('warning')
                ->icon('heroicon-o-clock'),
            Stat::make('Selesai Ditinjau', $selesaiDitinjau)
                ->description('Total surat yang telah Anda setujui atau tolak')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
