<x-guest-layout>
    {{-- <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg"> --}}

        {{-- Header Form dengan Logo dan Judul --}}
        <div class="text-center">
            <h2 class="mt-6 text-2xl font-bold text-gray-900">
                Buat Akun Baru
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Daftar untuk mulai melacak servis dan melihat riwayat Anda.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            {{-- Tombol Register --}}
            <div class="mt-6">
                <x-primary-button class="w-full flex justify-center">
                    {{ __('Daftar') }}
                </x-primary-button>
            </div>
        </form>

        {{-- Link ke Halaman Login --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Sudah punya akun?
                <a class="font-medium text-primary hover:underline" href="{{ route('login') }}">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>