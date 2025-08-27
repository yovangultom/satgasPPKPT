<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                <div
                    class="p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 p-3 bg-yellow-100 rounded-full">
                            <svg class="w-5 h-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-base font-bold text-gray-700">Belum Dikerjakan</h5>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $pengaduanBelumDikerjakan->count() }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">Total pengaduan yang menunggu untuk diproses</p>
                        </div>
                    </div>
                </div>

                <div
                    class="p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 p-3 bg-blue-100 rounded-full">
                            <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 11.667 0l3.181-3.183m-4.991-2.691v-4.992m0 0h-4.992m4.992 0-3.181-3.183a8.25 8.25 0 0 0-11.667 0L2.985 9.348Z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-base font-bold text-gray-700">Sedang Dikerjakan</h5>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $pengaduanSedangDikerjakan->count() }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">Total pengaduan yang sedang dalam proses</p>
                        </div>
                    </div>
                </div>

                <div
                    class="p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 p-3 bg-green-100 rounded-full">
                            <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-base font-bold text-gray-700">Selesai Dikerjakan</h5>
                            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $pengaduanSelesai->count() }}</p>
                            <p class="mt-1 text-sm text-gray-500">Total pengaduan yang telah selesai</p>
                        </div>
                    </div>
                </div>

            </div>
            <div x-data="{ tab: 'belum' }" class="p-4 sm:p-6 bg-white rounded-xl shadow-lg mt-5">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-2 p-1 bg-gray-100 rounded-lg">
                        <button @click="tab = 'belum'"
                            :class="{ 'bg-white shadow text-blue-600': tab === 'belum', 'text-gray-500 hover:text-gray-800': tab !== 'belum' }"
                            class="px-4 py-2 text-sm font-semibold rounded-md transition-colors">
                            Belum Dikerjakan
                        </button>
                        <button @click="tab = 'proses'"
                            :class="{ 'bg-white shadow text-blue-600': tab === 'proses', 'text-gray-500 hover:text-gray-800': tab !== 'proses' }"
                            class="px-4 py-2 text-sm font-semibold rounded-md transition-colors">
                            Sedang Dikerjakan
                        </button>
                        <button @click="tab = 'selesai'"
                            :class="{ 'bg-white shadow text-blue-600': tab === 'selesai', 'text-gray-500 hover:text-gray-800': tab !== 'selesai' }"
                            class="px-4 py-2 text-sm font-semibold rounded-md transition-colors">
                            Selesai Dikerjakan
                        </button>
                    </div>

                    <form class="flex items-center w-full sm:w-auto">
                        <input
                            class="block w-full px-4 py-2 text-sm border-gray-300 rounded-l-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            type="search" placeholder="Search">
                        <button
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            type="submit">Search</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <div x-show="tab === 'belum'" x-cloak>
                        <x-pengaduan-table :items="$pengaduanBelumDikerjakan"
                            empty-message="Tidak ada pengaduan yang belum dikerjakan." />
                    </div>

                    <div x-show="tab === 'proses'" x-cloak>
                        <x-pengaduan-table :items="$pengaduanSedangDikerjakan"
                            empty-message="Tidak ada pengaduan yang sedang dikerjakan." />
                    </div>

                    <div x-show="tab === 'selesai'" x-cloak>
                        <x-pengaduan-table :items="$pengaduanSelesai"
                            empty-message="Tidak ada pengaduan yang selesai dikerjakan." />
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
