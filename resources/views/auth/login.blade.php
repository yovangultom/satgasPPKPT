<x-guest-layout>
    <div class="min-h-screen bg-gray-100 text-gray-800 flex flex-col justify-center items-center p-4">

        <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-6 sm:p-8">

            <a href="/" class="flex justify-center mb-6">
                <img src="{{ asset('images/Logo PPKPT 2025 Square Black.png') }}" alt="Logo PPKPT" class="w-auto h-24">
            </a>

            <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-900 mb-4">
                Selamat Datang
            </h1>
            <p class="text-center text-gray-500 mb-8">
                Silakan masuk untuk melanjutkan
            </p>

            <div class="mb-6">
                <button type="button"
                    class="w-full flex justify-center items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path
                            d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                        <path fill-rule="evenodd"
                            d="M3.087 9l.54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Login Via SSO ITERA</span>
                </button>
            </div>

            <div class="flex items-center my-4">
                <hr class="flex-grow border-gray-300">
                <span class="mx-4 text-sm font-medium text-gray-400">atau</span>
                <hr class="flex-grow border-gray-300">
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-input-label for="email" value="Email" class="font-semibold" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autofocus autocomplete="username" placeholder="contoh@email.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" value="Password" class="font-semibold" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="current-password" placeholder="********" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <div class="flex items-center justify-end mt-6">
                    <x-primary-button
                        class="w-full flex justify-center py-3 ms-3 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
            @if (Route::has('register'))
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}"
                            class="font-semibold text-blue-600 hover:text-blue-800 underline">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <div class="text-center mt-6 text-sm text-gray-500">
            &copy; {{ date('Y') }} PPKPT 2025. All Rights Reserved.
        </div>
    </div>
</x-guest-layout>
