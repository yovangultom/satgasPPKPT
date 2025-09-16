<x-guest-layout>
    <div class="min-h-screen bg-gray-100 text-gray-800 flex flex-col justify-center items-center p-4">

        <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-6 sm:p-8">

            <a href="/" class="flex justify-center mb-6">
                <img src="{{ asset('images/Logo PPKPT 2025 Square Black.png') }}" alt="Logo PPKPT" class="w-auto h-24">
            </a>

            <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 mb-4">
                Buat Akun Baru
            </h1>
            <p class="text-center text-gray-500 mb-8">
                Isi data di bawah untuk mendaftar.
            </p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div>
                    <x-input-label for="name" value="Nama Lengkap" class="font-semibold" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required autofocus autocomplete="name"
                        placeholder="Masukkan nama lengkap Anda" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" value="Email" class="font-semibold" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username" placeholder="contoh@email.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" value="Password" class="font-semibold" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" placeholder="Minimal 8 karakter" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" class="font-semibold" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password"
                        placeholder="Ketik ulang password Anda" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-8">
                    <x-primary-button
                        class="w-full flex justify-center py-3 bg-gray-800 hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>

                <div class="text-center mt-6">
                    <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500" href="{{ route('login') }}">
                        {{ __('Sudah punya akun? Masuk di sini') }}
                    </a>
                </div>

            </form>
        </div>

        <div class="text-center mt-6 text-sm text-gray-500">
            &copy; SATGAS PPKPT {{ date('Y') }} . All Rights Reserved.
        </div>
    </div>
</x-guest-layout>
