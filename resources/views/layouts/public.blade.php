<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pangkalan Komputer ID')</title>

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Jika Anda pakai Lightbox di banyak halaman, letakkan link-nya di sini --}}
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" /> --}}
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
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script> --}}
    @stack('scripts') {{-- Untuk script spesifik per halaman --}}
</body>
</html>