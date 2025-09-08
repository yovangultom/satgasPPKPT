<?php

namespace App\Filament\Resources\PengaduanResource\Pages;

use App\Filament\Resources\PengaduanResource;
use App\Notifications\PengaduanStatusUpdated;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Pengaduan;
use App\Models\Pelapor;
use App\Models\Terlapor;
use App\Models\Korban;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection as IlluminateCollection;
use App\Filament\Resources\PengaduanResource\RelationManagers;
use Illuminate\Support\Carbon;
use App\Models\SuratPanggilan;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Filament\Resources\PengaduanResource\RelationManagers\BorangPenanganansRelationManager;




class ViewPengaduan extends ViewRecord
{
    protected static string $resource = PengaduanResource::class;
    protected static ?string $title = 'Detail Pengaduan';
    protected static ?string $breadcrumb = 'Detail';
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetak_pdf')
                ->label('PDF')
                ->color('success')
                ->icon('heroicon-o-document-arrow-down')
                ->url(route('pengaduan.pdf', ['pengaduan' => $this->record]))
                ->openUrlInNewTab(),
        ];
    }
    public function getRelationManagers(): array
    {
        return [
            RelationManagers\MessagesRelationManager::class,
            RelationManagers\SuratPanggilanRelationManager::class,
            RelationManagers\BorangPenanganansRelationManager::class,
            RelationManagers\BeritaAcaraPemeriksaansRelationManager::class,
            RelationManagers\BorangPemeriksaansRelationManager::class,
            RelationManagers\LaporanHasilPemeriksaansRelationManager::class,
            RelationManagers\SuratRekomendasisRelationManager::class,


        ];
    }
}
