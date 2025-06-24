<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Dashboard' }} | Pangkalan Komputer ID</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        {{-- <link rel="icon" media="(max-width: 640px)" src="{{ asset('images/logoP.png') }}"> --}}
        <link rel="icon" href="{{ asset('images/logoP.png') }}" type="image/x-icon">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="{{ asset('vendor/lightbox2/css/lightbox.css') }}" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            {{-- @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset --}}

            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
    {{-- Jika header slot ada di layout Breeze --}}
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif
</div>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="{{ asset('vendor/lightbox2/js/lightbox.js') }}"></script>
    <script>
        lightbox.option({
        'resizeDuration': 100,
        'wrapAround': true,
        'albumLabel': "Gambar %1 dari %2"
        })
    </script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@auth
    <div x-data="{ open: false }" class="sm:hidden fixed bottom-5 right-5 z-50"> {{-- sm:hidden berarti hanya tampil di mobile --}}

        {{-- Dropdown Panel (akan muncul di atas tombol) --}}
        <div x-show="open"
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute bottom-full right-0 mb-2 w-80 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
             style="display: none;">

            {{-- Isi Dropdown --}}
            <div class="px-4 py-2 font-bold border-b flex justify-between items-center">
                <span class="text-sm">Notifikasi</span>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    {{-- <a href="{{ route('notifications.markAllAsRead') }}" class="text-xs text-primary hover:underline">Tandai semua dibaca</a> --}}
                @endif
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse(Auth::user()->notifications()->take(7)->get() as $notification)
                    <a href="{{ route('notifications.read', $notification->id) }}" 
                       class="flex items-start px-4 py-3 hover:bg-gray-100 {{ !$notification->read_at ? 'font-semibold bg-indigo-50' : '' }}">

                        <div class="w-full">
                            <p class="text-sm leading-snug text-gray-800">{{ $notification->data['message'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->timezone('Asia/Jakarta')->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-3 text-sm text-gray-500 text-center">Tidak ada notifikasi.</div>
                @endforelse
            </div>
            <div class="px-4 py-2 text-xs text-center border-t bg-gray-50 rounded-b-lg">
                <a href="{{ route('notifications.index') }}" class="text-indigo-600 hover:underline">Lihat Semua Notifikasi</a>
            </div>
        </div>

        {{-- Tombol Lonceng Melayang --}}
        <button @click="open = !open" class="relative flex items-center justify-center h-16 w-16 bg-primary rounded-full text-white shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark">
            <span class="sr-only">Buka Notifikasi</span>
            <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>

            @if(Auth::user()->unreadNotifications->count() > 0)
                <span class="absolute top-1 right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex items-center justify-center w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full">{{ Auth::user()->unreadNotifications->count() }}</span>
                </span>
            @endif
        </button>
    </div>
    @endauth

@stack('scripts') {{-- Tambahkan ini --}}
    </body>
</html>
