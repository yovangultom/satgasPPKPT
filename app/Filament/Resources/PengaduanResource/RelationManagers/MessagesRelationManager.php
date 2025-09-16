<?php

namespace App\Filament\Resources\PengaduanResource\RelationManagers;

use App\Filament\Resources\PengaduanResource;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Forms\Components\Hidden;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action as NotificationAction;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';
    protected static ?string $title = 'Pesan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('body')->label('Isi Pesan')->rows(3)->requiredWithout('file_path'),
                Forms\Components\FileUpload::make('file_path')
                    ->label('Lampiran File')
                    ->disk('public')
                    ->directory('attachments')
                    ->acceptedFileTypes(['application/zip', 'application/vnd.rar', 'application/x-rar-compressed', 'image/jpeg', 'image/png'])
                    ->maxSize(5120)
                    ->afterStateUpdated(function (Set $set, ?\Livewire\Features\SupportFileUploads\TemporaryUploadedFile $state) {
                        if ($state) {
                            $set('file_name', $state->getClientOriginalName());
                            $set('file_mime_type', $state->getMimeType());
                        }
                    })
                    ->preserveFilenames()
                    ->requiredWithout('body'),
                Hidden::make('file_name'),
                Hidden::make('file_mime_type'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Pengirim')->weight('bold'),
                Tables\Columns\TextColumn::make('body')->label('Pesan')->wrap()->placeholder('Tidak ada pesan teks.'),
                Tables\Columns\TextColumn::make('file_name')->label('Lampiran')->placeholder('Tidak ada lampiran.'),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu Kirim')->dateTime('d M Y, H:i'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Kirim Pesan Baru')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->modalHeading('Buat Pesan')
                    ->modalSubmitActionLabel('Kirim')
                    ->modalCancelActionLabel('Batal')
                    ->authorize(true)
                    ->createAnother(false)
                    ->closeModalByClickingAway(false)
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    })
                    ->after(function (Model $record, array $data) {
                        try {
                            $pengaduan = $this->getOwnerRecord();
                            $currentUser = auth()->user();
                            $adminsAndPetugas = User::role(['admin', 'petugas'])
                                ->where('id', '!=', $currentUser->id)
                                ->get();

                            if ($adminsAndPetugas->isNotEmpty()) {
                                FilamentNotification::make()
                                    ->title('Pesan baru dari ' . $currentUser->name)
                                    ->icon('heroicon-o-envelope')
                                    ->body('Pesan baru ditambahkan pada pengaduan ' . $pengaduan->nomor_pengaduan)
                                    ->actions([
                                        NotificationAction::make('Lihat')
                                            ->url(PengaduanResource::getUrl('view', ['record' => $pengaduan->id]))
                                            ->markAsRead(),
                                    ])
                                    ->sendToDatabase($adminsAndPetugas);
                            }

                            $pelapor = $pengaduan->user;
                            if ($pelapor && $pelapor->id !== $currentUser->id) {
                                $pelapor->notify(new NewMessageNotification($pengaduan, $record));
                            }
                        } catch (\Exception $e) {
                            Log::error('Gagal mengirim notifikasi pesan baru: ' . $e->getMessage());
                        }
                    }),
            ])
            ->actions([
                TableAction::make('download')->label('Unduh')->icon('heroicon-o-arrow-down-tray')->color('primary')
                    ->visible(fn(Model $record) => !is_null($record->file_path))
                    ->action(fn(Model $record) => Storage::disk('public')->download($record->file_path, $record->file_name)),
                DeleteAction::make(),
            ]);
    }
}
