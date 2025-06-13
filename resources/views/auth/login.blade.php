<x-guest-layout>
    <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">

        {{-- Header Form dengan Logo --}}
        <div class="text-center">
            
            <h2 class="mt-6 text-2xl font-bold text-gray-900">
                Selamat Datang Kembali!
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Silakan masuk ke akun Anda untuk melanjutkan.
            </p>
        </div>

        <x-auth-session-status class="my-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-primary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div>

            {{-- Tombol Login --}}
            <div class="mt-6">
                <x-primary-button class="w-full flex justify-center">
                    {{ __('Log In') }}
                </x-primary-button>
            </div>
        </form>

        {{-- Link ke Halaman Registrasi --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Belum punya akun?
                <a class="font-medium text-primary hover:underline" href="{{ route('register') }}">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>