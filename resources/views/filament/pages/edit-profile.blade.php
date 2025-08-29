<x-filament-panels::page>

    {{-- Form untuk Foto Profil --}}
    <form wire:submit.prevent="updatePhoto" class="mt-10">
        {{ $this->photoForm }}
        <div class="mt-6">
            <x-filament-panels::form.actions :actions="$this->getUpdatePhotoFormActions()" alignment="right" />
        </div>
    </form>

    {{-- Form untuk Tanda Tangan --}}
    <form wire:submit.prevent="updateSignature" class="mt-10">
        {{ $this->signatureForm }}
        <div class="mt-6">
            <x-filament-panels::form.actions :actions="$this->getUpdateSignatureFormActions()" alignment="right" />
        </div>
    </form>

    {{-- Form untuk Password --}}
    <form wire:submit.prevent="updatePassword" class="mt-10">
        {{ $this->passwordForm }}
        <div class="mt-6">
            <x-filament-panels::form.actions :actions="$this->getUpdatePasswordFormActions()" alignment="right" />
        </div>
    </form>

</x-filament-panels::page>
