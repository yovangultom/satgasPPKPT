<x-filament-panels::page>
    <form wire:submit.prevent="updateProfile">
        {{ $this->profileForm }}
        <div class="mt-4 flex justify-end">
            <x-filament::button type="submit" class="mt-6">
                Simpan Tanda Tangan
            </x-filament::button>
        </div>
    </form>

    <form wire:submit.prevent="updatePassword" class="mt-8">
        {{ $this->passwordForm }}
        <div class="mt-4 flex justify-end">
            <x-filament::button type="submit" class="mt-6">
                Ubah Password
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
