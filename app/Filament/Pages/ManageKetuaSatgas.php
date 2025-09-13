<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\KetuaSatgas;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ManageKetuaSatgas extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    protected static string $view = 'filament.pages.manage-ketua-satgas';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $title = 'Ketua Satgas';
    protected static ?string $navigationLabel = 'Ketua Satgas';

    public $data = [];

    public function mount(): void
    {
        $record = KetuaSatgas::firstOrNew([]);
        $this->form->fill($record->toArray());
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required(),

                Forms\Components\TextInput::make('nip')
                    ->label('NIP / NRK')
                    ->nullable(),

                Forms\Components\FileUpload::make('tanda_tangan')
                    ->label('Tanda Tangan (Format: .png)')
                    ->image()
                    ->disk('public')
                    ->directory('tanda-tangan-satgas')
                    ->nullable(),
            ])
        ];
    }

    public function submit(): void
    {
        $formData = $this->form->getState();

        $record = KetuaSatgas::firstOrNew([]);

        $record->fill($formData);

        $record->save();

        Notification::make()
            ->title('Data Ketua Satgas berhasil diperbarui.')
            ->success()
            ->send();
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
