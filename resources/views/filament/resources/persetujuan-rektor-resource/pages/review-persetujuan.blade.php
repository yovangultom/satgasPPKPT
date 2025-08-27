<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Dokumen Surat Rekomendasi</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Silakan tinjau dokumen di bawah ini. Anda dapat
                        mengubah ukuran area PDF dengan menarik sudut kanan bawah.</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 p-2">
                    <div style="resize: vertical; overflow: auto; min-height: 50vh; max-height: 95vh; height: 75vh;"
                        class="rounded-lg border dark:border-gray-600">
                        <iframe src="{{ route('surat_rekomendasi.export-pdf', ['suratRekomendasi' => $record]) }}"
                            class="w-full h-full" frameborder="0">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
