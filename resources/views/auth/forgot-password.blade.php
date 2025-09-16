<x-guest-layout>
    <div class="min-h-screen bg-gray-100 text-gray-800 flex flex-col justify-center items-center p-4">

        <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-6 sm:p-8">

            <a href="/" class="flex justify-center mb-6">
                <img src="{{ asset('images/Logo PPKPT 2025 Square Black.png') }}" alt="Logo PPKPT" class="w-auto h-24">
            </a>

            <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 mb-4">
                Lupa Password?
            </h1>
            <p class="text-center text-gray-500 mb-6">
                Tidak masalah. Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
            </p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div>
                    <x-input-label for="email" value="Alamat Email" class="font-semibold" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autofocus placeholder="Masukkan email terdaftar Anda" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-8">
                    <x-primary-button
                        class="w-full flex justify-center py-3 bg-gray-800 hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900">
                        {{ __('Kirim Tautan Reset Password') }}
                    </x-primary-button>
                </div>

                <div class="text-center mt-6">
                    <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500" href="{{ route('login') }}">
                        <span aria-hidden="true">&larr;</span>
                        {{ __(' Kembali ke Login') }}
                    </a>
                </div>

            </form>
        </div>
        <div class="text-center mt-6 text-sm text-gray-500">
            &copy; SATGAS PPKPT {{ date('Y') }} . All Rights Reserved.
        </div>
    </div>
</x-guest-layout>
