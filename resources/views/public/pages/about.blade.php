@extends('layouts.public')

@section('title', 'Tentang Kami - Pangkalan Komputer ID')

@section('content')
<div class="bg-white">
    {{-- Hero Section --}}
    <div class="relative isolate px-6 pt-14 lg:px-8">
        <div class="mx-auto max-w-2xl py-24 sm:py-32">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Tentang Pangkalan Komputer ID</h1>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Solusi terpercaya untuk setiap kebutuhan teknologi Anda, dari perbaikan presisi hingga penyediaan komponen berkualitas.
                </p>
            </div>
        </div>
    </div>

    {{-- Konten Utama --}}
    <div class="overflow-hidden bg-white py-12 sm:py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                <div class="lg:pr-8 lg:pt-4">
                    <div class="lg:max-w-lg">
                        <h2 class="text-base font-semibold leading-7 text-indigo-600">Sejarah Kami</h2>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Berawal dari Semangat & Garasi</p>
                        <p class="mt-6 text-lg leading-8 text-gray-600">
                            Pangkalan Komputer ID lahir pada tahun 2024 dari sebuah garasi kecil di Bandung. Didirikan oleh [Nama Pendiri], seorang teknisi dengan pengalaman bertahun-tahun dan semangat untuk memberikan layanan servis komputer yang jujur, transparan, dan berkualitas. Kami melihat banyaknya kebutuhan akan layanan servis yang bisa dipercaya, di mana pelanggan tidak hanya mendapatkan perbaikan, tetapi juga edukasi dan ketenangan pikiran.
                        </p>
                        <dl class="mt-10 max-w-xl space-y-8 text-base leading-7 text-gray-600 lg:max-w-none">
                            <div class="relative pl-9">
                                <dt class="inline font-semibold text-gray-900">
                                    <svg class="absolute left-1 top-1 h-5 w-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.5 17a4.5 4.5 0 01-1.44-8.765 4.5 4.5 0 018.302-3.046a4.5 4.5 0 014.502 4.502A4.5 4.5 0 0117.5 17h-12a.5.5 0 010-1h12a3.5 3.5 0 10-6.95-2.402a3.513 3.513 0 00-3.05-2.402A3.5 3.5 0 006.5 13H5.5a.5.5 0 010 1h-1a.5.5 0 010-1H2.5a.5.5 0 010-1h1a.5.5 0 010 1h.5a.5.5 0 010 1H.5a.5.5 0 010-1h1.5a.5.5 0 010 1h.5a.5.5 0 010 1H.5a.5.5 0 01-.5-.5v-1a.5.5 0 01.5-.5h14a.5.5 0 010 1h-1.5a.5.5 0 010-1H6.5a.5.5 0 010-1h1a.5.5 0 010-1H5.5a.5.5 0 010-1zM2.5 12a.5.5 0 01.5-.5h14a.5.5 0 010 1h-14a.5.5 0 01-.5-.5z" clip-rule="evenodd" />
                                    </svg>
                                    Visi Kami.
                                </dt>
                                <dd class="inline">Menjadi mitra teknologi nomor satu di Bandung yang dikenal karena keandalan, integritas, dan inovasi dalam layanan.</dd>
                            </div>
                            <div class="relative pl-9">
                                <dt class="inline font-semibold text-gray-900">
                                     <svg class="absolute left-1 top-1 h-5 w-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                         <path d="M4.75 3A1.75 1.75 0 003 4.75v10.5c0 .966.784 1.75 1.75 1.75h10.5A1.75 1.75 0 0017 15.25V4.75A1.75 1.75 0 0015.25 3H4.75zM8.75 6.25a.75.75 0 000 1.5h2.5a.75.75 0 000-1.5h-2.5zM7.5 10.75a.75.75 0 01.75-.75h4a.75.75 0 010 1.5h-4a.75.75 0 01-.75-.75zM7.75 13a.75.75 0 000 1.5h4a.75.75 0 000-1.5h-4z"/>
                                     </svg>
                                    Misi Kami.
                                </dt>
                                <dd class="inline">Memberikan solusi servis yang cepat, akurat, dan transparan; menyediakan produk berkualitas; dan membangun komunitas yang teredukasi seputar teknologi.</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726a?q=80&w=2070" alt="Product screenshot" class="w-[48rem] max-w-none rounded-xl shadow-xl ring-1 ring-gray-400/10 sm:w-[57rem] md:-ml-4 lg:-ml-0" width="2432" height="1442">
            </div>
        </div>
    </div>
</div>
@endsection