@extends('layouts.public')

@section('title', ($selectedCategory ? $selectedCategory->name . ' - ' : '') . 'Layanan Kami - Pangkalan Komputer ID')

@section('content')
<div class="bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Header Halaman --}}
        <header class="mb-8 text-center">
            @if ($selectedCategory)
                <h1 class="text-4xl font-bold tracking-tight text-gray-900">Layanan dalam Kategori: <span class="text-primary">{{ $selectedCategory->name }}</span></h1>
                <p class="mt-2 text-lg text-gray-600">{{ $selectedCategory->description }}</p>
                <a href="{{ route('services.catalog') }}" class="mt-4 inline-block text-sm font-medium text-primary hover:text-primary-dark">&larr; Lihat Semua Kategori Layanan</a>
            @else
                <h1 class="text-4xl font-bold tracking-tight text-gray-900">Layanan Jasa Servis Kami</h1>
                <p class="mt-2 text-lg text-gray-600">Solusi profesional untuk semua kebutuhan perbaikan dan perawatan komputer Anda.</p>
            @endif
        </header>

        {{-- Filter Kategori --}}
        <div class="mb-10 max-w-2xl mx-auto">
            <form action="{{ route('services.catalog') }}" method="GET" class="space-y-4">
                {{-- Pencarian --}}
                <div>
                    <label for="search" class="sr-only">Cari Layanan</label>
                    <div class="flex rounded-md shadow-sm">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="flex-1 block w-full border-gray-300 rounded-l-md focus:border-primary focus:ring-primary sm:text-sm" placeholder="Cari layanan (misal: ganti keyboard)...">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark">Cari</button>
                    </div>
                </div>
                {{-- Filter Kategori --}}
                <div class="text-center">
                    <span class="text-sm text-gray-500 mr-2">Filter Kategori:</span>
                    <a href="{{ route('services.catalog', ['search' => request('search')]) }}" class="inline-block px-3 py-1 rounded-full text-xs font-medium border {{ !$selectedCategory ? 'bg-primary text-white border-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">Semua</a>
                    @foreach ($serviceCategories as $category)
                        <a href="{{ route('services.catalog', ['kategori' => $category->slug, 'search' => request('search')]) }}" class="inline-block px-3 py-1 rounded-full text-xs font-medium border {{ $selectedCategory && $selectedCategory->id == $category->id ? 'bg-primary text-white border-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            </form>
        </div>

        {{-- Grid Daftar Jasa Servis --}}
        @if($services && $services->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($services as $service)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 ease-in-out p-6 flex flex-col">
                    {{-- Ikon Layanan --}}
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-primary text-white">
                            {{-- Placeholder Icon --}}
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1-1-3.87-3.87a3.87 3.87 0 010-5.47L9 2l1-1 3.75.87M9 2L3 8m6 9l6-6m-6 6L6 12m3 9l6 6"></path></svg>
                        </span>
                    </div>
                    <div class="mt-4 flex-grow">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('services.show-public', $service->slug) }}" class="hover:text-primary">
                                {{ $service->name }}
                            </a>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500 flex-grow">{{ Str::limit($service->description, 120) ?: 'Deskripsi layanan akan segera tersedia.' }}</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 space-y-2 text-sm">
                         <div class="flex justify-between">
                            <span class="font-medium text-gray-500">Estimasi Harga:</span>
                            <span class="font-semibold text-gray-800">{{ $service->estimated_price ? 'Rp ' . number_format($service->estimated_price, 0, ',', '.') : 'Hubungi Kami' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-500">Estimasi Durasi:</span>
                            <span class="font-semibold text-gray-800">{{ $service->estimated_duration ?: 'Tergantung Kondisi' }}</span>
                        </div>
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('services.show-public', $service->slug) }}" class="block w-full text-center bg-indigo-50 text-primary font-semibold py-2 px-4 rounded-lg hover:bg-indigo-100 transition-colors">
                            Lihat Detail Layanan
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $services->links() }} {{-- Paginasi --}}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-lg shadow-md">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900">Tidak Ada Layanan</h3>
                <p class="mt-1 text-sm text-gray-500">Belum ada layanan yang ditemukan dalam kategori ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection