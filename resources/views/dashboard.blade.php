<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="p-8 md:w-1/2 text-center md:text-left">
                        <h1 class="text-3xl font-bold text-gray-900 ">
                            APLIKASI PENGADUAN KEKERASAN <br>SATGAS PPKPT <br> INSTITUT TEKNOLOGI SUMATERA
                        </h1>
                        <p class="mt-4 text-gray-600 dark:text-gray-600">
                            Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>! Kami hadir untuk
                            memastikan lingkungan kampus yang aman dan nyaman bagi semua. Jika Anda
                            mengalami atau menyaksikan tindakan yang tidak pantas, jangan ragu untuk melapor!
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-600">Mari kita bersama-sama ciptakan Itera yang
                            lebih aman dan inklusif! 💙
                        </p>
                        <div class="mt-8">
                            <a href="{{ route('lapor.create') }}"
                                class="inline-block px-5 py-3 bg-blue-600 text-white font-bold text-sm rounded-lg shadow-md hover:bg-blue-700 transition-transform transform hover:scale-105 duration-300 ease-in-out">
                                Buat Laporan Baru
                            </a>
                        </div>
                    </div>
                    <div class="md:w-1/2 p-6 flex justify-center items-center ">
                        <img src="{{ asset('images/dashboard.png') }}" alt="Ilustrasi layanan dan pengaduan masyarakat"
                            class="w-full max-w-md h-auto object-cover rounded-lg ">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
