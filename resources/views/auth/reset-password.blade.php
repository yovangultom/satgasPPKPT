<x-guest-layout>
    <div class="min-h-screen bg-gray-100 text-gray-800 flex flex-col justify-center items-center p-4">

        <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-6 sm:p-8">

            <a href="/" class="flex justify-center mb-6">
                <img src="{{ asset('images/Logo PPKPT 2025 Square Black.png') }}" alt="Logo PPKPT" class="w-auto h-24">
            </a>

            <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 mb-4">
                Atur Ulang Password
            </h1>
            <p class="text-center text-gray-500 mb-8">
                Silakan masukkan password baru Anda di bawah ini.
            </p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <x-input-label for="email" value="Email" class="font-semibold" />
                    <x-text-input id="email" class="block mt-1 w-full bg-gray-100 cursor-not-allowed" type="email"
                        name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" readonly />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" value="Password Baru" class="font-semibold" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" placeholder="Masukkan password baru" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" class="font-semibold" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password"
                        placeholder="Ketik ulang password baru" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-8">
                    <x-primary-button
                        class="w-full flex justify-center py-3 bg-indigo-600 hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700">
                        {{ __('Atur Ulang Password') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <div class="text-center mt-6 text-sm text-gray-500">
            &copy; SATGAS PPKPT {{ date('Y') }} . All Rights Reserved.
        </div>
    </div>
</x-guest-layout>
