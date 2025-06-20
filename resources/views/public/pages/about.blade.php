@extends('layouts.public')

@section('title', 'Tentang Kami - Pangkalan Komputer ID')

@section('content')
    {{-- ================================== --}}
    {{--         1. HERO SECTION            --}}
    {{-- ================================== --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-gray-900 via-primary-dark to-gray-800 animate-gradient-xy">
        {{-- Elemen Latar Belakang (Opsional) --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gray-500 mix-blend-overlay"></div>
        </div>

        {{-- Konten Utama Hero Section --}}
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <div class="text-center">
                {{-- Kita gunakan style="animation-fill-mode:backwards;" agar elemen tersembunyi sebelum animasi dimulai --}}
                {{-- dan animation-delay untuk membuat animasi muncul berurutan --}}
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl animate-fade-in-up" style="animation-fill-mode: backwards; animation-delay: 0.2s;">
                    Mengenal Kami Lebih Dekat
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-300 animate-fade-in-up" style="animation-fill-mode: backwards; animation-delay: 0.1s;">
                    Kami bukan sekadar toko servis. Kami adalah partner teknologi Anda yang berdedikasi untuk memberikan layanan yang andal, transparan, dan penuh integritas.
                </p>
                
            </div>
        </div>
    </div>

    {{-- ================================== --}}
    {{--        2. SEKSI SEJARAH            --}}
    {{-- ================================== --}}
    <div class="bg-white py-16 sm:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <div class="mx-auto grid max-w-2xl grid-cols-1 items-start gap-x-8 gap-y-16 sm:gap-y-24 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                <div class="lg:pr-4">
                    <div class="relative overflow-hidden rounded-3xl bg-gray-900 px-6 pb-9 pt-64 shadow-2xl sm:px-12 lg:max-w-lg lg:px-8 lg:pb-8 xl:px-10 xl:pb-10">
                        <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?q=80&w=1964" alt="Workspace Teknisi">
                    </div>
                </div>
                <div>
                    <div class="text-base leading-7 text-gray-700">
                        <p class="text-base font-semibold leading-7 text-primary">Kisah Kami</p>
                        <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Berawal dari Semangat & Garasi</h2>
                        <div class="max-w-xl space-y-6 mt-6">
                            <p>
                                Pangkalan Komputer ID lahir dari sebuah garasi kecil di Bandung dengan satu misi sederhana: menjadi tempat servis komputer yang bisa benar-benar dipercaya. Berbekal pengalaman bertahun-tahun dan semangat untuk membantu, kami ingin mengubah paradigma servis yang seringkali tidak transparan.
                            </p>
                            <p>
                                Kami percaya bahwa setiap pelanggan berhak tahu apa yang terjadi pada perangkat kesayangan mereka. Oleh karena itu, kami membangun sistem ini sebagai wujud komitmen kami pada transparansi, memberikan Anda akses penuh untuk melacak setiap langkah perbaikan dengan bukti nyata.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================== --}}
    {{--        3. VISI & MISI             --}}
    {{-- ================================== --}}
    <div class="bg-gray-50 py-16 sm:py-24">
         <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-primary">Nilai Kami</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Fondasi Kerja Pangkalan Komputer ID</p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-2">
                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <svg class="h-5 w-5 flex-none text-primary" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.5 17a4.5 4.5 0 01-1.44-8.765 4.5 4.5 0 018.302-3.046a4.5 4.5 0 014.502 4.502A4.5 4.5 0 0117.5 17h-12a.5.5 0 010-1h12a3.5 3.5 0 10-6.95-2.402a3.513 3.513 0 00-3.05-2.402A3.5 3.5 0 006.5 13H5.5a.5.5 0 010 1h-1a.5.5 0 010-1H2.5a.5.5 0 010-1h1a.5.5 0 010 1h.5a.5.5 0 010 1H.5a.5.5 0 010-1h1.5a.5.5 0 010 1h.5a.5.5 0 010 1H.5a.5.5 0 01-.5-.5v-1a.5.5 0 01.5-.5h14a.5.5 0 010 1h-1.5a.5.5 0 010-1H6.5a.5.5 0 010-1h1a.5.5 0 010-1H5.5a.5.5 0 010-1z" clip-rule="evenodd" /></svg>
                            Visi Kami
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Menjadi mitra teknologi nomor satu di Bandung yang dikenal karena keandalan, integritas, dan inovasi dalam setiap layanan yang kami berikan.</p>
                        </dd>
                    </div>
                    <div class="flex flex-col">
                         <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <svg class="h-5 w-5 flex-none text-primary" viewBox="0 0 20 20" fill="currentColor"><path d="M4.75 3A1.75 1.75 0 003 4.75v10.5c0 .966.784 1.75 1.75 1.75h10.5A1.75 1.75 0 0017 15.25V4.75A1.75 1.75 0 0015.25 3H4.75zM8.75 6.25a.75.75 0 000 1.5h2.5a.75.75 0 000-1.5h-2.5zM7.5 10.75a.75.75 0 01.75-.75h4a.75.75 0 010 1.5h-4a.75.75 0 01-.75-.75zM7.75 13a.75.75 0 000 1.5h4a.75.75 0 000-1.5h-4z"/></svg>
                            Misi Kami
                        </dt>
                         <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Memberikan solusi servis yang cepat, akurat, dan 100% transparan; menyediakan produk IT berkualitas; dan membangun komunitas yang teredukasi seputar teknologi melalui platform kami.</p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
@endsection