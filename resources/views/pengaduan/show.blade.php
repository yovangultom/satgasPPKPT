<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">

                    <!-- Tombol Aksi -->
                    <div class="flex justify-between items-center mb-6 pb-4 border-b">
                        <a href="{{ route('pengaduan.index') }}"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                            &larr; Kembali ke Daftar Pengaduan
                        </a>

                    </div>

                    <!-- Header Detail Pengaduan -->
                    <div class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nomor Pengaduan</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ $pengaduan->nomor_pengaduan ?? 'Tidak ada' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Laporan</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ $pengaduan->tanggal_pelaporan->format('d F Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-lg font-semibold">
                                    {{-- Anda bisa menyesuaikan warna badge sesuai status --}}
                                    <span
                                        class="px-3 py-1 text-sm rounded-full 
                                        @if ($pengaduan->status_pengaduan == 'Pending') bg-yellow-100 text-yellow-800 @endif
                                        @if ($pengaduan->status_pengaduan == 'Selesai') bg-green-100 text-green-800 @endif
                                        @if (in_array($pengaduan->status_pengaduan, ['Verifikasi', 'Investigasi'])) bg-blue-100 text-blue-800 @endif
                                    ">
                                        {{ $pengaduan->status_pengaduan }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                    </div>
                    <!-- Seksi Pelapor, Korban, dan Terlapor -->
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Pihak Terkait</h3>

                        {{-- Data Pelapor --}}
                        @foreach ($pengaduan->pelapors as $pelapor)
                            <div class="mb-4 p-4 border rounded-lg">
                                <h4 class="font-semibold text-black mb-2">Pelapor: {{ $pelapor->nama }}
                                    ({{ $pelapor->pivot->peran_dalam_pengaduan }})
                                </h4>
                                <p class="text-sm text-gray-800 ">Nomor Telepon: {{ $pelapor->nomor_telepon }}</p>
                                <p class="text-sm text-gray-800">Jenis Kelamin: {{ $pelapor->jenis_kelamin }}</p>
                                <p class="text-sm text-gray-800">Domisili: {{ $pelapor->domisili }}</p>
                                <p class="text-sm text-gray-800">Memiliki Disabilitas:
                                    {{ $pelapor->memiliki_disabilitas }}</p>
                                <p class="text-sm text-gray-800">Status: {{ $pelapor->status }}</p>

                            </div>
                        @endforeach
                        @if ($pengaduan->pelapors->first() && $pengaduan->pelapors->first()->pivot->peran_dalam_pengaduan === 'Saksi')
                            {{-- Data Korban --}}
                            @if ($pengaduan->korbans->isNotEmpty())
                                @foreach ($pengaduan->korbans as $korban)
                                    <div class="mb-4 p-4 border rounded-lg bg-green-50">
                                        <h4 class="font-semibold text-black mb-2">Korban: {{ $korban->nama }}
                                        </h4>
                                        <p class="text-sm text-gray-800 ">Nomor Telepon: {{ $korban->nomor_telepon }}
                                        </p>
                                        <p class="text-sm text-gray-800">Jenis Kelamin: {{ $korban->jenis_kelamin }}
                                        </p>
                                        <p class="text-sm text-gray-800">Domisili: {{ $korban->domisili }}</p>
                                        <p class="text-sm text-gray-800">Memiliki Disabilitas:
                                            {{ $korban->memiliki_disabilitas }}</p>
                                        <p class="text-sm text-gray-800">Status: {{ $korban->status }}</p>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                        {{-- Data Terlapor --}}
                        @if ($pengaduan->terlapors->isNotEmpty())
                            @foreach ($pengaduan->terlapors as $terlapor)
                                <div class="mb-4 p-4 border rounded-lg bg-red-50">
                                    <h4 class="font-semibold text-black mb-2">Terlapor: {{ $terlapor->nama }}</h4>
                                    <p class="text-sm text-gray-800 ">Nomor Telepon: {{ $terlapor->nomor_telepon }}</p>
                                    <p class="text-sm text-gray-800">Jenis Kelamin: {{ $terlapor->jenis_kelamin }}</p>
                                    <p class="text-sm text-gray-800">Domisili: {{ $terlapor->domisili }}</p>
                                    <p class="text-sm text-gray-800">Memiliki Disabilitas:
                                        {{ $terlapor->memiliki_disabilitas }}</p>
                                    <p class="text-sm text-gray-800">Status: {{ $terlapor->status }}</p>

                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="mt-6  border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Alasan dan Kebutuhan Korban</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Alasan Pengaduan</dt>
                                <dd class="mt-2 text-gray-900">
                                    @if (is_array($pengaduan->alasan_pengaduan) && !empty($pengaduan->alasan_pengaduan))
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($pengaduan->alasan_pengaduan as $alasan)
                                                <li>{{ $alasan }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-500">Tidak ada alasan yang disebutkan.</p>
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Kebutuhan Korban</dt>
                                <dd class="mt-2 text-gray-900">
                                    @if (is_array($pengaduan->identifikasi_kebutuhan_korban) && !empty($pengaduan->identifikasi_kebutuhan_korban))
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($pengaduan->identifikasi_kebutuhan_korban as $kebutuhan)
                                                <li>{{ $kebutuhan }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-500">Tidak ada kebutuhan yang disebutkan.</p>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Kejadian</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Jenis Kejadian</dt>
                                <dd class="mt-1 text-gray-900">{{ $pengaduan->jenis_kejadian }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Tanggal Kejadian</dt>
                                <dd class="mt-1 text-gray-900">{{ $pengaduan->tanggal_kejadian->format('d F Y') }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Lokasi Kejadian</dt>
                                <dd class="mt-1 text-gray-900">{{ $pengaduan->lokasi_kejadian }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Kronologi / Deskripsi Pengaduan</dt>
                                <dd class="mt-1 text-gray-900 whitespace-pre-wrap text-justify">
                                    {{ $pengaduan->deskripsi_pengaduan }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Bukti Pendukung</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Tanda Tangan Pelapor</dt>
                                <dd class="mt-2">
                                    @if ($pengaduan->tanda_tangan_pelapor_image_url)
                                        <img src="{{ $pengaduan->tanda_tangan_pelapor_image_url }}"
                                            alt="Tanda Tangan Pelapor" class="h-24 border rounded-md p-2">
                                    @else
                                        <p class="text-gray-500">Tidak ada gambar tanda tangan.</p>
                                    @endif
                                </dd>
                            </div>
                            @if ($pengaduan->bukti_pendukung)
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">File Bukti (.zip/.rar)</dt>
                                    <dd class="mt-1">
                                        <a href="{{ Storage::disk('public')->url($pengaduan->bukti_pendukung) }}"
                                            class="text-indigo-600 hover:underline" download>
                                            Bukti Pengaduan
                                        </a>
                                    </dd>
                                </div>
                            @endif
                            @if ($pengaduan->url_bukti_tambahan)
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">URL Bukti Tambahan</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $pengaduan->url_bukti_tambahan }}" target="_blank"
                                            rel="noopener noreferrer" class="text-indigo-600 hover:underline">
                                            Link
                                        </a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Pesan</h3>

                        <div x-data="{}" x-init="el = $el;
                        el.scrollTop = el.scrollHeight"
                            class="chat-container h-96 max-h-96 flex flex-col space-y-4 rounded-lg border border-gray-300 bg-gray-50 p-4 overflow-y-auto">

                            @forelse($pengaduan->messages as $message)
                                @php
                                    $isSender = $message->user_id === auth()->id();
                                @endphp

                                <div class="flex {{ $isSender ? 'justify-end' : 'justify-start' }}">

                                    <div
                                        class="max-w-[75%] md:max-w-[60%] rounded-lg p-3 {{ $isSender ? 'bg-green-100 text-gray-800' : 'bg-white border border-gray-200 text-gray-800' }}">

                                        <strong
                                            class="text-sm font-bold {{ $isSender ? 'text-green-800' : 'text-blue-800' }}">
                                            {{ $message->user->name }}
                                        </strong>

                                        @if ($message->body)
                                            <p class="mt-1 text-sm">{{ $message->body }}</p>
                                        @endif

                                        @if ($message->file_path)
                                            <a href="{{ Storage::url($message->file_path) }}" target="_blank"
                                                download="{{ $message->file_name }}"
                                                class="mt-2 flex items-center gap-2 rounded-lg bg-gray-200/50 p-2 text-sm text-blue-600 hover:bg-gray-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>{{ $message->file_name }}</span>
                                            </a>
                                        @endif

                                        <small class="mt-2 block text-right text-xs text-gray-500">
                                            {{ $message->created_at->format('d M H:i') }}
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <div class="flex h-full items-center justify-center">
                                    <p class="text-gray-500">Belum ada pesan. Mulailah percakapan. 🚀</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="form-kirim-pesan mt-4">
                            @if (session('success'))
                                <div class="mb-4 rounded-md bg-green-100 p-3 text-sm text-green-700">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('pengaduan.storeMessage', $pengaduan) }}" method="POST"
                                enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="body" class="sr-only">Pesan Anda</label>
                                    <textarea name="body" id="body" rows="5"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3"
                                        placeholder="Ketik pesan Anda di sini...">{{ old('body') }}</textarea>
                                    @error('body')
                                        <p class="mt-1 text-sm text-red-600 break-all">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="file" class="sr-only">Kirim Lampiran</label>
                                    <input type="file" name="file" id="file"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                                    <small class="mt-1 block text-xs text-gray-500 break-all">Tipe file yang
                                        diizinkan: zip, rar,
                                        jpg, png. Maks: 5MB.</small>
                                    @error('file')
                                        <p class="mt-1 text-sm text-red-600 break-all">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Kirim
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
