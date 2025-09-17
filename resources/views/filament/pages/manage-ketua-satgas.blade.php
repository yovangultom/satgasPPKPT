<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4" wire:loading.attr="disabled" wire:target="data.tanda_tangan">

            <div wire:loading wire:target="data.tanda_tangan" class="flex items-center">
                <x-filament::loading-indicator class="h-5 w-5 mr-2" />
                <span>Mengunggah file...</span>
            </div>

            <span wire:loading.remove wire:target="data.tanda_tangan">
                Simpan
            </span>
        </x-filament::button>
    </form>
</x-filament::page>
