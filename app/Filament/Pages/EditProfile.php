<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Hidden; // 1. Tambahkan use statement untuk Hidden
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;


class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.edit-profile';
    protected static ?string $title = 'Profil Saya';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $profileData = [];
    public ?array $passwordData = [];

    public function mount(): void
    {
        $this->profileForm->fill(auth()->user()->attributesToArray());
        $this->passwordForm->fill();
    }

    public function profileForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Profil')
                    ->schema([
                        TextInput::make('name')->label('Nama')->required()->disabled(),
                        TextInput::make('email')->label('Email')->email()->required()->disabled(),
                        Hidden::make('tanda_tangan'),

                        // [PERUBAHAN] Hapus baris ->viewData(...) dari sini
                        ViewField::make('signature_ui')
                            ->label('Tanda Tangan Digital Anda')
                            ->view('filament.forms.components.signature-pad'),
                    ])
            ])
            ->model(auth()->user())
            ->statePath('profileData');
    }

    public function passwordForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ubah Password')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->required()
                            ->currentPassword(),
                        TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->dehydrateStateUsing(fn($state): string => Hash::make($state))
                            ->live(debounce: 500)
                            ->same('new_password_confirmation'),
                        TextInput::make('new_password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->required()
                            ->dehydrated(false),
                    ])
            ])
            ->statePath('passwordData');
    }

    protected function getForms(): array
    {
        return [
            'profileForm',
            'passwordForm',
        ];
    }

    // 5. Logika updateProfile sekarang akan berfungsi karena menerima data dari Hidden input
    public function updateProfile(): void
    {
        $data = $this->profileForm->getState();
        $user = auth()->user();

        if (!empty($data['tanda_tangan']) && Str::startsWith($data['tanda_tangan'], 'data:image')) {
            if ($user->tanda_tangan && Storage::disk('public')->exists($user->tanda_tangan)) {
                Storage::disk('public')->delete($user->tanda_tangan);
            }

            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['tanda_tangan']));
            $filename = 'tanda-tangan/' . $user->id . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $imageData);
            $data['tanda_tangan'] = $filename;
        } else {
            unset($data['tanda_tangan']);
        }

        $user->update($data);

        $this->sendSuccessNotification('Profil Berhasil Diperbarui');
        $this->dispatch('profile-updated');
        $this->profileForm->fill($user->attributesToArray());
    }

    public function updatePassword(): void
    {
        $data = $this->passwordForm->getState();
        auth()->user()->update(['password' => $data['new_password']]);
        $this->sendSuccessNotification('Password berhasil diubah.');
        $this->passwordForm->fill();
    }

    private function sendSuccessNotification(string $message): void
    {
        Notification::make()
            ->success()
            ->title($message)
            ->send();
    }
}
