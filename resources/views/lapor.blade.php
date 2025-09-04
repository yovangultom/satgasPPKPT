<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="formController()" x-init="init()">
                <div class="bg-white  shadow-xl sm:rounded-lg p-8">
                    <form id="laporForm" @submit.prevent="submitForm" action="{{ route('lapor.store') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                <p class="font-bold">Terdapat Kesalahan Pada Input Anda:</p>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="">
                            <h1 class="text-xl font-bold leading-7 text-black">Form Laporan Pengaduan</h1>
                        </div>
                        <div class="">
                            <div class=" border-gray-900/10 pb-2">
                                <h2 class="text-xl font-semibold leading-7 text-gray-900">Data Pelapor</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">Informasi ini akan digunakan untuk
                                    komunikasi lebih lanjut. Kerahasiaan Anda dijamin.</p>
                            </div>
                            <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6 border-t pt-3">
                                <div class="sm:col-span-3">
                                    <label for="pelapor_nama"
                                        class="block text-sm font-medium leading-6 text-gray-900">Nama
                                        Lengkap</label>
                                    <input type="text" id="pelapor_nama" name="pelapor[nama]"
                                        value="{{ Auth::user()->name }}" readonly
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-500 bg-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6">

                                </div>
                                <div class="sm:col-span-3">
                                    <label for="pelapor_telepon"
                                        class="block text-sm font-medium leading-6 text-gray-900">Nomor Telepon</label>
                                    <input type="tel" id="pelapor_telepon" name="pelapor[nomor_telepon]" required
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="sm:col-span-3 relative z-30">
                                    <label for="pelapor_jenis_kelamin"
                                        class="block text-sm font-medium leading-6 text-gray-900">Jenis Kelamin</label>
                                    <select id="pelapor_jenis_kelamin" name="pelapor[jenis_kelamin]" required
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option>Laki-laki</option>
                                        <option>Perempuan</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="pelapor_domisili"
                                        class="block text-sm font-medium leading-6 text-gray-900">Domisili</label>
                                    <input type="text" id="pelapor_domisili" name="pelapor[domisili]" required
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="pelapor_status"
                                        class="block text-sm font-medium leading-6 text-gray-900">Status Anda</label>
                                    <select id="pelapor_status" name="pelapor[status]" required
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option>Mahasiswa</option>
                                        <option>Dosen</option>
                                        <option>Tendik</option>
                                        <option>Warga Kampus</option>
                                        <option>Masyarakat Umum</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="pelapor_memiliki_disabilitas"
                                        class="block text-sm font-medium leading-6 text-gray-900">Apakah memiliki
                                        disabilitas?</label>
                                    <select id="pelapor_memiliki_disabilitas" name="pelapor[memiliki_disabilitas]"
                                        required
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="pelapor_peran"
                                        class="block text-sm font-medium leading-6 text-gray-900">Peran Anda</label>

                                    <select id="pelapor_peran" name="pelapor[peran]" required x-model="peranPelapor"
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="Korban">Saya adalah Korban</option>
                                        <option value="Saksi">Saya adalah Saksi</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <template x-if="peranPelapor === 'Saksi'">
                            <div x-transition class="">
                                <div class=" border-gray-900/10 pb-2">
                                    <h2 class="text-xl font-semibold leading-7 text-gray-900">Data Korban <span
                                            class="text-sm font-normal text-gray-500">(Wajib diisi jika Anda
                                            Saksi)</span>
                                    </h2>
                                    <p class="mt-1 text-sm leading-6 text-gray-600">Anda bisa menambahkan lebih dari
                                        satu
                                        korban.</p>
                                </div>
                                <template x-for="(korban, index) in korbans" :key="index">
                                    <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6 border-t pt-3">
                                        <div class="sm:col-span-3">
                                            <label :for="'korban_nama_' + index"
                                                class="block text-sm font-medium leading-6 text-gray-900"
                                                x-text="'Nama Lengkap Korban ' + (index + 1)"></label>
                                            <input type="text" :id="'korban_nama_' + index"
                                                :name="'korbans[' + index + '][nama]'" x-model="korban.nama" required
                                                class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label :for="'korban_telepon_' + index"
                                                class="block text-sm font-medium leading-6 text-gray-900">Nomor
                                                Telepon
                                                Korban</label>
                                            <input type="tel" :id="'korban_telepon_' + index"
                                                :name="'korbans[' + index + '][nomor_telepon]'"
                                                x-model="korban.nomor_telepon" required
                                                class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label :for="'korban_jenis_kelamin_' + index"
                                                class="block text-sm font-medium leading-6 text-gray-900">Jenis
                                                Kelamin</label>
                                            <select :id="'korban_jenis_kelamin_' + index"
                                                :name="'korbans[' + index + '][jenis_kelamin]'"
                                                x-model="korban.jenis_kelamin" required
                                                class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option>Laki-laki</option>
                                                <option>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label :for="'korban_domisili_' + index"
                                                class="block text-sm font-medium leading-6 text-gray-900">Domisili</label>
                                            <input type="text" :id="'korban_domisili_' + index"
                                                :name="'korbans[' + index + '][domisili]'" x-model="korban.domisili"
                                                required
                                                class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                        <div class="col-span-3">
                                            <label :for="'korban_status_' + index"
                                                class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                                            <select :id="'korban_status_' + index"
                                                :name="'korbans[' + index + '][status]'" x-model="korban.status"
                                                required
                                                class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option>Mahasiswa</option>
                                                <option>Dosen</option>
                                                <option>Tendik</option>
                                                <option>Warga Kampus</option>
                                                <option>Masyarakat Umum</option>
                                            </select>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label :for="'korban_disabilitas_' + index"
                                                class="block text-sm font-medium leading-6 text-gray-900">Apakah
                                                memiliki
                                                disabilitas?</label>
                                            <select :id="'korban_disabilitas_' + index"
                                                :name="'korbans[' + index + '][memiliki_disabilitas]'"
                                                x-model="korban.memiliki_disabilitas" required
                                                class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="0">Tidak</option>
                                                <option value="1">Ya</option>
                                            </select>
                                        </div>
                                        <div class="sm:col-span-6 flex justify-end">
                                            <button @click.prevent="korbans.splice(index, 1)"
                                                x-show="korbans.length > 1"
                                                class="rounded-md bg-red-50 px-2 py-2 text-sm font-semibold text-red-600 shadow-sm hover:bg-red-100">Hapus
                                                Korban</button>
                                        </div>
                                    </div>
                                </template>
                                <div class="mt-3 border-t border-gray-200 pt-3">
                                    <button
                                        @click.prevent="korbans.push({ nama: '', nomor_telepon: '', jenis_kelamin: '', domisili: '', status: '', memiliki_disabilitas: ''  })"
                                        class="rounded-md bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-600 shadow-sm hover:bg-indigo-100">Tambah
                                        Korban Lain</button>
                                </div>
                            </div>
                        </template>

                        <div class="">
                            <div class=" border-gray-900/10 pb-2">
                                <h2 class="text-xl font-semibold leading-7 text-gray-900">Data Terlapor</h2>
                            </div>
                            <template x-for="(terlapor, index) in terlapors" :key="index">
                                <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6 border-t pt-3">
                                    <div class="sm:col-span-3">
                                        <label :for="'terlapor_nama_' + index"
                                            class="block text-sm font-medium leading-6 text-gray-900"
                                            x-text="'Nama Lengkap Terlapor ' + (index + 1)"></label>
                                        <input type="text" :id="'terlapor_nama_' + index"
                                            :name="'terlapors[' + index + '][nama]'" x-model="terlapor.nama" required
                                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label :for="'terlapor_nomor_telepon_' + index"
                                            class="block text-sm font-medium leading-6 text-gray-900">Nomor
                                            Telepon</label>
                                        <input type="tel" :id="'terlapor_nomor_telepon_' + index"
                                            :name="'terlapors[' + index + '][nomor_telepon]'"
                                            x-model="terlapor.nomor_telepon" required
                                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                    <div class="sm:col-span-3 relative z-30">
                                        <label :for="'terlapor_jenis_kelamin_' + index"
                                            class="block text-sm font-medium leading-6 text-gray-900">Jenis
                                            Kelamin</label>
                                        <select :id="'terlapor_jenis_kelamin_' + index"
                                            :name="'terlapors[' + index + '][jenis_kelamin]'"
                                            x-model="terlapor.jenis_kelamin" required
                                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option>Laki-laki</option>
                                            <option>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label :for="'terlapor_domisili_' + index"
                                            class="block text-sm font-medium leading-6 text-gray-900">Domisili</label>
                                        <input type="text" :id="'terlapor_domisili_' + index"
                                            :name="'terlapors[' + index + '][domisili]'" x-model="terlapor.domisili"
                                            required
                                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                    <div class="col-span-3">
                                        <label :for="'terlapor_status_' + index"
                                            class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                                        <select :id="'terlapor_status_' + index"
                                            :name="'terlapors[' + index + '][status]'" x-model="terlapor.status"
                                            required
                                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option>Mahasiswa</option>
                                            <option>Dosen</option>
                                            <option>Tendik</option>
                                            <option>Warga Kampus</option>
                                            <option>Masyarakat Umum</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label :for="'terlapor_disabilitas_' + index"
                                            class="block text-sm font-medium leading-6 text-gray-900">Apakah
                                            memiliki
                                            disabilitas?</label>
                                        <select :id="'terlapor_disabilitas_' + index"
                                            :name="'terlapors[' + index + '][memiliki_disabilitas]'"
                                            x-model="terlapor.memiliki_disabilitas" required
                                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option value="0">Tidak</option>
                                            <option value="1">Ya</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-6 flex justify-end">
                                        <button @click.prevent="terlapors.splice(index, 1)"
                                            x-show="terlapors.length > 1"
                                            class="rounded-md bg-red-50 px-2 py-2 text-sm font-semibold text-red-600 shadow-sm hover:bg-red-100">Hapus
                                            Terlapor</button>
                                    </div>
                                </div>
                            </template>
                            <div class="mt-3 border-t border-gray-200 pt-3">
                                <button
                                    @click.prevent="terlapors.push({ nama: '', nomor_telepon: '', jenis_kelamin: '', domisili: '', status: '',memiliki_disabilitas: '' })"
                                    class="rounded-md bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-600 shadow-sm hover:bg-indigo-100">Tambah
                                    Terlapor</button>
                            </div>
                        </div>

                        <div class="">
                            <div class="border-b border-gray-900/10 pb-2">
                                <h2 class="text-xl font-semibold leading-7 text-gray-900">Detail Kejadian</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">Jelaskan insiden yang terjadi
                                    dengan
                                    detail.</p>
                            </div>
                            <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="jenis_kejadian"
                                        class="block text-sm font-medium leading-6 text-gray-900">Jenis
                                        Kejadian</label>
                                    <select id="jenis_kejadian" name="jenis_kejadian" required
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option>Kekerasan fisik</option>
                                        <option>Kekerasan psikis</option>
                                        <option>Perundungan</option>
                                        <option>Kekerasan seksual</option>
                                        <option>Kebijakan yang mengandung kekerasan</option>
                                        <option>Diskriminasi dan intoleransi</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="tanggal_kejadian"
                                        class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                                        Kejadian
                                        (Perkiraan)</label>
                                    <input type="date" id="tanggal_kejadian" name="tanggal_kejadian" required
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="terjadi_saat_tridharma"
                                        class="block text-sm font-medium leading-6 text-gray-900">
                                        Apakah kejadian kekerasan terjadi saat pelaksanaan Tri Dharma?
                                    </label>
                                    <select id="terjadi_saat_tridharma" name="terjadi_saat_tridharma" required
                                        x-model="terjadiSaatTriDharma"
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="1">Ya</option>
                                        <option value="0">Tidak</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Pendidikan, Penelitian, dan Pengabdian.</p>
                                </div>
                                <div x-show="terjadiSaatTriDharma === '1'" x-transition class="sm:col-span-3">
                                    <label for="jenis_tridharma"
                                        class="block text-sm font-medium leading-6 text-gray-900">
                                        Apa jenis Tri Dharma yang berlangsung?
                                    </label>
                                    <select id="jenis_tridharma" name="jenis_tridharma"
                                        :required="terjadiSaatTriDharma === '1'"
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option>Pendidikan</option>
                                        <option>Penelitian</option>
                                        <option>Pengabdian</option>
                                    </select>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="terjadi_di_wilayah_kampus"
                                        class="block text-sm font-medium leading-6 text-gray-900">
                                        Apakah kejadian kekerasan terjadi di wilayah kampus?
                                    </label>
                                    <select id="terjadi_di_wilayah_kampus" name="terjadi_di_wilayah_kampus" required
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="1">Ya</option>
                                        <option value="0">Tidak</option>
                                    </select>
                                </div>
                                <div class="col-span-full">
                                    <label for="lokasi_kejadian"
                                        class="block text-sm font-medium leading-6 text-gray-900">Lokasi
                                        Kejadian</label>
                                    <input type="text" id="lokasi_kejadian" name="lokasi_kejadian" required
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <div class="col-span-full">
                                    <label for="deskripsi_pengaduan"
                                        class="block text-sm font-medium leading-6 text-gray-900">Deskripsi /
                                        Kronologi
                                        Kejadian</label>
                                    <textarea id="deskripsi_pengaduan" name="deskripsi_pengaduan" rows="5" required
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                </div>
                                <div class="col-span-full">
                                    <label for="bukti_pendukung"
                                        class="block text-sm font-medium leading-6 text-gray-900">Unggah Bukti
                                        Pendukung
                                        (.zip/.rar, maks 10MB)</label>
                                    <input type="file" id="bukti_pendukung" name="bukti_pendukung" required
                                        accept=".zip,.rar" @change="validateFileSize($event)"
                                        class="block w-full text-sm text-gray-900 rounded-md  p-1.5 border border-gray-300  cursor-pointer bg-gray-50 focus:outline-none">
                                    <x-input-error :messages="$errors->get('bukti_pendukung')" class="mt-2" />
                                    <template x-if="fileError">
                                        <p x-text="fileError" class="text-sm text-red-600 mt-2"></p>
                                    </template>

                                </div>
                                <div class="col-span-full">
                                    <label for="url_bukti_tambahan"
                                        class="block text-sm font-medium leading-6 text-gray-900">URL Bukti
                                        Tambahan
                                        (misal: Google Drive)</label>
                                    <input type="url" id="url_bukti_tambahan" name="url_bukti_tambahan"
                                        class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <div class="border-b border-gray-900/10 pb-2">
                                <h2 class="text-xl font-semibold leading-7 text-gray-900">Alasan Melapor &
                                    Kebutuhan
                                    Korban
                                </h2>
                            </div>
                            <div class="mt-3 space-y-10">
                                <fieldset>
                                    <legend class="text-sm font-semibold leading-6 text-gray-900">Alasan
                                        Pengaduan
                                        Anda
                                        (Pilih yang sesuai)</legend>
                                    <div class="mt-4 space-y-4">
                                        <div class="relative flex gap-x-3">
                                            <div class="flex h-6 items-center"><input id="alasan1"
                                                    name="alasan_pengaduan[]"
                                                    value="Saya seorang saksi yang khawatir dengan keadaan korban"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            </div>
                                            <div class="text-sm leading-6"><label for="alasan1"
                                                    class="font-medium text-gray-900">Saya seorang saksi yang
                                                    khawatir
                                                    dengan keadaan korban</label></div>
                                        </div>
                                        <div class="relative flex gap-x-3">
                                            <div class="flex h-6 items-center"><input id="alasan2"
                                                    name="alasan_pengaduan[]"
                                                    value="Saya seorang korban yang memerlukan bantuan pemulihan"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            </div>
                                            <div class="text-sm leading-6"><label for="alasan2"
                                                    class="font-medium text-gray-900">Saya seorang korban yang
                                                    memerlukan
                                                    bantuan pemulihan</label></div>
                                        </div>
                                        <div class="relative flex gap-x-3">
                                            <div class="flex h-6 items-center"><input id="alasan3"
                                                    name="alasan_pengaduan[]"
                                                    value="Saya ingin ITERA menindak tegas terlapor" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            </div>
                                            <div class="text-sm leading-6"><label for="alasan3"
                                                    class="font-medium text-gray-900">Saya ingin ITERA menindak
                                                    tegas
                                                    terlapor</label></div>
                                        </div>
                                        <div class="relative flex gap-x-3">
                                            <div class="flex h-6 items-center"><input id="alasan4"
                                                    name="alasan_pengaduan[]"
                                                    value="Saya ingin satuan tugas PPKPT mendokumentasikan kejadiannya, meningkatkan keamanan ITERA dari kekerasan seksual, dan memberi perlindungan bagi saya"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            </div>
                                            <div class="text-sm leading-6"><label for="alasan4"
                                                    class="font-medium text-gray-900">Saya ingin satuan tugas
                                                    PPKPT
                                                    mendokumentasikan kejadiannya, meningkatkan keamanan ITERA
                                                    dari
                                                    kekerasan seksual, dan memberi perlindungan bagi
                                                    saya</label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend class="text-sm font-semibold leading-6 text-gray-900">Identifikasi
                                        Kebutuhan
                                        Korban (Pilih yang sesuai)</legend>
                                    <div class="mt-4 space-y-4">
                                        <div class="relative flex gap-x-3">
                                            <div class="flex h-6 items-center"><input id="kebutuhan1"
                                                    name="identifikasi_kebutuhan_korban[]"
                                                    value="Konseling psikologis" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            </div>
                                            <div class="text-sm leading-6"><label for="kebutuhan1"
                                                    class="font-medium text-gray-900">Konseling
                                                    psikologis</label>
                                            </div>
                                        </div>
                                        <div class="relative flex gap-x-3">
                                            <div class="flex h-6 items-center"><input id="kebutuhan2"
                                                    name="identifikasi_kebutuhan_korban[]"
                                                    value="Konseling rohani/spiritual" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            </div>
                                            <div class="text-sm leading-6"><label for="kebutuhan2"
                                                    class="font-medium text-gray-900">Konseling
                                                    rohani/spiritual</label>
                                            </div>
                                        </div>
                                        <div class="relative flex gap-x-3">
                                            <div class="flex h-6 items-center"><input id="kebutuhan3"
                                                    name="identifikasi_kebutuhan_korban[]" value="Bantuan hukum"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            </div>
                                            <div class="text-sm leading-6"><label for="kebutuhan3"
                                                    class="font-medium text-gray-900">Bantuan hukum</label>
                                            </div>
                                        </div>
                                        <div class="relative flex gap-x-3">
                                            <div class="flex h-6 items-center"><input id="kebutuhan4"
                                                    name="identifikasi_kebutuhan_korban[]" value="Bantuan medis"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            </div>
                                            <div class="text-sm leading-6"><label for="kebutuhan4"
                                                    class="font-medium text-gray-900">Bantuan medis</label>
                                            </div>
                                        </div>
                                        <div class="relative flex gap-x-3">
                                            <div class="flex h-6 items-center"><input id="kebutuhan5"
                                                    name="identifikasi_kebutuhan_korban[]" value="Bantuan digital"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            </div>
                                            <div class="text-sm leading-6"><label for="kebutuhan5"
                                                    class="font-medium text-gray-900">Bantuan digital</label>
                                            </div>
                                        </div>
                                        <div class="relative flex gap-x-3">
                                            <div class="flex h-6 items-center"><input id="kebutuhan6"
                                                    name="identifikasi_kebutuhan_korban[]"
                                                    value="Tidak membutuhkan pendampingan" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            </div>
                                            <div class="text-sm leading-6"><label for="kebutuhan6"
                                                    class="font-medium text-gray-900">Tidak membutuhkan
                                                    pendampingan</label></div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="">
                            <div class="border-b border-gray-900/10 pb-6">
                                <h2 class="text-xl font-semibold leading-7 text-gray-900">Persetujuan & Tanda
                                    Tangan
                                </h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">Saya menyatakan bahwa informasi
                                    yang
                                    saya
                                    berikan adalah benar dan dapat dipertanggungjawabkan.</p>
                            </div>
                            <div class="mt-6">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Tanda Tangan
                                    Pelapor:</label>
                                <div class="mt-2 w-full border border-gray-300 rounded-lg touch-none">
                                    <canvas x-ref="signatureCanvas" class="w-full h-48 rounded-lg"></canvas>
                                </div>
                                <input type="hidden" name="tanda_tangan_pelapor" x-model="signatureData" required>
                                <div class="mt-2 flex items-center justify-end">
                                    <button type="button" @click="clearSignature"
                                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Bersihkan</button>
                                </div>
                                <template x-if="signatureError">
                                    <p x-text="signatureError" class="text-sm text-red-600 mt-2"></p>
                                </template>
                            </div>
                        </div>

                        <div class=" flex items-center justify-end gap-x-6">
                            <button type="button" @click.prevent="showConfirmationModal = true"
                                class="rounded-md bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Kirim Laporan
                            </button>
                        </div>

                        <div x-show="showConfirmationModal" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 bg-gray-500 bg-opacity-75 transition-opacity"
                            style="display: none;" x-cloak>

                            <div class="fixed inset-0 flex items-center justify-center p-4">

                                <div @click.away="showConfirmationModal = false" x-show="showConfirmationModal"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="relative w-full max-w-lg overflow-hidden rounded-lg bg-white shadow-xl transform transition-all">

                                    <div class="p-6 text-center sm:text-left">
                                        <div class="sm:flex sm:items-center">
                                            <div
                                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-yellow-600"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                </svg>
                                            </div>

                                            <div class="mt-3 sm:mt-0 sm:ml-4">
                                                <h3 class="text-lg font-semibold leading-6 text-gray-900"
                                                    id="modal-title">
                                                    Konfirmasi Pengiriman Laporan
                                                </h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500">
                                                        Apakah Anda yakin semua data yang Anda masukkan sudah benar dan
                                                        dapat dipertanggungjawabkan?
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="flex flex-col-reverse gap-y-3 px-6 py-4 bg-gray-50 sm:flex-row sm:justify-end sm:gap-x-3">
                                        <button type="button" @click="showConfirmationModal = false"
                                            class="inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">
                                            Periksa Kembali
                                        </button>
                                        <button type="button" @click="submitForm()"
                                            class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto">
                                            Ya, Saya Yakin
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('formController', () => ({
                    peranPelapor: 'Korban',
                    korbans: [],
                    terjadiSaatTriDharma: '1',
                    showConfirmationModal: false,
                    terlapors: [{
                        nama: '',
                        nomor_telepon: '',
                        jenis_kelamin: '',
                        domisili: '',
                        status: '',
                        memiliki_disabilitas: '',

                    }],

                    signaturePad: null,
                    signatureData: '',
                    signatureError: '',
                    fileError: '',


                    init() {
                        this.$watch('peranPelapor', value => {
                            if (value === 'Saksi' && this.korbans.length === 0) {
                                this.korbans.push({
                                    nama: '',
                                    nomor_telepon: '',
                                    jenis_kelamin: '',
                                    domisili: '',
                                    status: '',
                                    memiliki_disabilitas: ''
                                });
                            }
                        });

                        this.$nextTick(() => {
                            const canvas = this.$refs.signatureCanvas;
                            const ratio = Math.max(window.devicePixelRatio || 1, 1);
                            canvas.width = canvas.offsetWidth * ratio;
                            canvas.height = canvas.offsetHeight * ratio;
                            canvas.getContext('2d').scale(ratio, ratio);
                            this.signaturePad = new SignaturePad(canvas, {
                                backgroundColor: 'rgb(255, 255, 255)'
                            });
                        });
                    },

                    clearSignature() {
                        if (this.signaturePad) this.signaturePad.clear();
                    },
                    validateFileSize(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const maxSize = 10485760;
                            if (file.size > maxSize) {
                                this.fileError = 'Ukuran file tidak boleh melebihi 10MB.';
                                event.target.value = null;
                            } else {
                                this.fileError = '';
                            }
                        }
                    },

                    submitForm() {

                        this.validateFileSize({
                            target: document.getElementById('bukti_pendukung')
                        });
                        if (this.signaturePad.isEmpty()) {
                            this.signatureError = 'Tanda tangan wajib diisi.';
                            return;
                        }
                        if (this.fileError) {
                            return;
                        }

                        this.signatureData = this.signaturePad.toDataURL('image/png');
                        this.signatureError = '';

                        this.$nextTick(() => {
                            document.getElementById('laporForm').submit();
                        });
                    }
                }));
            });
        </script>
    @endpush
</x-app-layout>
