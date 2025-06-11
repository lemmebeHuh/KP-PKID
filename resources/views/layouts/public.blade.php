<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pangkalan Komputer ID')</title>

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Jika Anda pakai Lightbox di banyak halaman, letakkan link-nya di sini --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
    <link href="{{ asset('vendor/lightbox2/css/lightbox.css') }}" rel="stylesheet" />
</head>
<body class="bg-gray-100 font-sans">
    
    {{-- Navigasi Utama Publik --}}
    @include('layouts.partials.public-navigation')

    {{-- Konten Halaman --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer Publik --}}
    @include('layouts.partials.public-footer')

    {{-- Scripts di akhir body --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    @stack('scripts') {{-- Untuk script spesifik per halaman --}}
    {{-- Tambahkan Alpine.js jika belum ada di dalam app.js Anda --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @push('scripts')
    {{-- Jika Anda menggunakan Lightbox, pastikan scriptnya sudah di-load di layout utama --}}
    <script src="{{ asset('vendor/lightbox2/js/lightbox.js') }}"></script>
    <script>
        lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': "Gambar %1 dari %2"
        })
    </script>
@endpush
</body>
</html>