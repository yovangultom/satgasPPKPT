<?php

namespace App\Filament\Resources\PengaduanResource\Widgets;

use App\Models\Pengaduan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;


class PengaduanStatsOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return Auth::user()->hasAnyRole(['admin', 'petugas', 'penanggung jawab']);
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Belum Dikerjakan', Pengaduan::where('status_pengaduan', 'Menunggu')->count())
                ->description('Total pengaduan yang menunggu untuk diproses')
                ->descriptionIcon('heroicon-m-inbox')
                ->color('gray'),

            Stat::make('Sedang Dikerjakan', Pengaduan::whereIn('status_pengaduan', [
                'Verifikasi',
                'Investigasi',
                'Penyusunan Kesimpulan dan Rekomendasi',
                'Tindak Lanjut Kesimpulan dan Rekomendasi'
            ])->count())
                ->description('Total pengaduan yang sedang dalam proses')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),

            Stat::make('Selesai Dikerjakan', Pengaduan::where('status_pengaduan', 'Selesai')->count())
                ->description('Total pengaduan yang telah selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
