<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\FileUpload;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.edit-profile';
    protected static ?string $title = 'Profil Saya';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $photoData = [];
    public ?array $profileData = [];
    public ?array $passwordData = [];

    public function mount(): void
    {
        $this->photoForm->fill(auth()->user()->attributesToArray());
        $this->signatureForm->fill(auth()->user()->attributesToArray());
        $this->passwordForm->fill();
    }

    public function photoForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Profil')
                    ->schema([
                        FileUpload::make('foto_profil')
                            ->label('Foto Profil')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->disk('public')
                            ->directory('foto-profil'),
                        TextInput::make('name')->label('Nama')->required()->disabled(),
                        TextInput::make('email')->label('Email')->email()->required()->disabled(),
                    ])
            ])
            ->model(auth()->user())
            ->statePath('photoData');
    }

    public function signatureForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tanda Tangan Digital')
                    ->schema([
                        Hidden::make('tanda_tangan'),
                        ViewField::make('signature_ui')
                            ->label('Tanda Tangan Anda')
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
                        TextInput::make('current_password')->label('Password Saat Ini')->password()->required()->currentPassword(),
                        TextInput::make('new_password')->label('Password Baru')->password()->required()->rule(Password::default())->autocomplete('new-password')->dehydrateStateUsing(fn($state): string => Hash::make($state))->live(debounce: 500)->same('new_password_confirmation'),
                        TextInput::make('new_password_confirmation')->label('Konfirmasi Password Baru')->password()->required()->dehydrated(false),
                    ])
            ])
            ->statePath('passwordData');
    }

    protected function getForms(): array
    {
        return [
            'photoForm',
            'signatureForm',
            'passwordForm',
        ];
    }

    public function getUpdatePhotoFormActions(): array
    {
        return [
            Action::make('updatePhoto')
                ->label('Simpan Perubahan')
                ->submit('updatePhoto'),
        ];
    }

    public function getUpdateSignatureFormActions(): array
    {
        return [
            Action::make('updateSignature')
                ->label('Simpan Tanda Tangan')
                ->submit('updateSignature'),
        ];
    }

    public function getUpdatePasswordFormActions(): array
    {
        return [
            Action::make('updatePassword')
                ->label('Ubah Password')
                ->submit('updatePassword'),
        ];
    }

    public function updatePhoto(): void
    {
        $data = $this->photoForm->getState();
        $user = auth()->user();

        if ($user->foto_profil && ($data['foto_profil'] ?? null)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->update($data);

        $this->sendSuccessNotification('Foto Profil Berhasil Diperbarui');

        // --- SOLUSI: Paksa refresh halaman dari backend dengan cache buster ---
        $this->redirect(static::getUrl() . '?v=' . time());
    }


    public function updateSignature(): void
    {
        $data = $this->signatureForm->getState();
        $user = auth()->user();

        if (!empty($data['tanda_tangan']) && Str::startsWith($data['tanda_tangan'], 'data:image')) {
            if ($user->tanda_tangan && Storage::disk('public')->exists($user->tanda_tangan)) {
                Storage::disk('public')->delete($user->tanda_tangan);
            }

            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['tanda_tangan']));
            $filename = 'tanda-tangan/' . $user->id . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $imageData);
            $data['tanda_tangan'] = $filename;

            $user->update($data);
            $this->sendSuccessNotification('Tanda Tangan Berhasil Diperbarui');
        } else {
            Notification::make()->warning()->title('Tidak ada perubahan tanda tangan.')->send();
        }
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
        Notification::make()->success()->title($message)->send();
    }
}
