@php
    $existingSignaturePath = $getLivewire()->profileData['tanda_tangan'] ?? null;
@endphp

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
        Tanda Tangan Saat Ini
    </label>
    <div
        class="mt-1 p-4 border border-gray-300 dark:border-gray-600 rounded-md min-h-[100px] flex items-center justify-center">
        @if ($existingSignaturePath && Illuminate\Support\Facades\Storage::disk('public')->exists($existingSignaturePath))
            <img src="{{ asset('storage/' . $existingSignaturePath) }}" alt="Tanda Tangan Tersimpan"
                style="width: auto; height: 120px; ">
        @else
            <p class="text-gray-500">Belum ada tanda tangan.</p>
        @endif
    </div>
</div>

<div wire:ignore x-data="signaturePadManager($wire)" class="w-full">

    <label for="signature-pad-canvas" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
        Gambar Tanda Tangan Baru (Akan menimpa yang lama)
    </label>
    <div class="border border-gray-300 dark:border-gray-600 rounded-md">
        <canvas x-ref="canvas" id="signature-pad-canvas" class="rounded-md w-full"></canvas>
    </div>
    <div class="mt-2 flex justify-end">
        <button type="button" @click="clear()"
            class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            Hapus Tanda Tangan
        </button>
    </div>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('signaturePadManager', ($wire) => ({
                    signaturePad: null,

                    init() {
                        this.$nextTick(() => {
                            const canvas = this.$refs.canvas;
                            canvas.style.height = '200px';

                            this.signaturePad = new SignaturePad(canvas);

                            this.resizeCanvas();
                            window.addEventListener('resize', () => this.resizeCanvas());
                            this.signaturePad.addEventListener('endStroke', () => {
                                const dataUrl = this.signaturePad.toDataURL('image/png');
                                $wire.set('profileData.tanda_tangan', dataUrl,
                                    false);
                            });
                        });
                    },

                    resizeCanvas() {
                        const canvas = this.$refs.canvas;
                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                        const parentWidth = canvas.parentElement.offsetWidth;

                        if (parentWidth > 0) {
                            canvas.width = parentWidth * ratio;
                            canvas.height = canvas.offsetHeight * ratio;
                            canvas.getContext('2d').scale(ratio, ratio);
                        }

                        this.signaturePad.clear();
                    },

                    clear() {
                        if (this.signaturePad) {
                            this.signaturePad.clear();
                        }
                        $wire.set('profileData.tanda_tangan', null, false);
                    }
                }));
            });
        </script>
    @endpush
@endonce
