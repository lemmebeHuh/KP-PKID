@extends('layouts.public')

@section('title', ($selectedCategory ? $selectedCategory->name . ' - ' : '') . 'Katalog Produk - Pangkalan Komputer ID')

@section('content')
<div x-data="{ openFilter: false }" class="bg-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header Halaman --}}
        <header class="mb-6">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900">Katalog Produk</h1>
            <p class="mt-2 text-lg text-gray-600">Temukan produk terbaik untuk kebutuhan teknologi Anda.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Sidebar untuk Filter (Desktop) --}}
            <aside class="hidden lg:block">
                <h3 class="font-semibold text-gray-900 mb-4 border-b pb-2">Kategori Produk</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('products.catalog', ['search' => request('search')]) }}" 
                           class="block px-3 py-2 rounded-md text-sm {{ !$selectedCategory ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-200' }}">
                            Semua Kategori
                        </a>
                    </li>
                    @foreach ($productCategories as $category)
                        <li>
                            <a href="{{ route('products.catalog', ['kategori' => $category->slug, 'search' => request('search')]) }}" 
                               class="block px-3 py-2 rounded-md text-sm {{ $selectedCategory && $selectedCategory->id == $category->id ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-200' }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                {{-- Filter lain bisa ditambahkan di sini (misal: rentang harga) --}}
            </aside>

            {{-- Konten Utama: Search, Hasil Produk --}}
            <main class="lg:col-span-3">
                {{-- Search & Tombol Filter Mobile --}}
                <form action="{{ route('products.catalog') }}" method="GET" id="filter-form">
    {{-- Pertahankan filter kategori jika ada --}}
    @if (request('kategori'))
        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
    @endif

    <div class="flex flex-col sm:flex-row items-center justify-between mb-6">
        {{-- Form Pencarian --}}
        <div class="w-full sm:max-w-xs">
            <div class="flex rounded-md shadow-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="flex-1 block w-full border-gray-300 rounded-l-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <button type="submit" class="inline-flex items-center px-3 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md text-gray-500 hover:bg-gray-200">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                </button>
                {{-- <button style="margin-left: 10px" class="inline-flex items-center px-3 bg-red-100 border-gray-300 rounded-r-md rounded-l-md text-white-500 hover:bg-red-300">
                    <a href="/produk">reset</a>
                </button> --}}
            </div>
        </div>

        {{-- DROPDOWN UNTUK SORTING --}}
        <div class="flex items-center mt-4 sm:mt-0">
            <label for="sort" class="mr-2 text-sm font-medium text-gray-700">Urutkan:</label>
            <select name="sort" id="sort" onchange="document.getElementById('filter-form').submit();" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
            </select>
        </div>

        {{-- Tombol Filter untuk Mobile (sudah ada) --}}
        <button @click="openFilter = !openFilter" type="button" class="lg:hidden mt-4 sm:mt-0 flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L12 14.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 016 17V6.414L3.293 3.707A1 1 0 013 3V4z" /></svg>
            Filter Kategori
        </button>
    </div>
</form> {{-- Penutup form utama --}}

                {{-- Header Hasil --}}
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h3 class="text-base text-gray-600">
                        Menampilkan <span class="font-semibold text-gray-900">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span> dari <span class="font-semibold text-gray-900">{{ $products->total() }}</span> hasil
                        @if($selectedCategory)
                            dalam kategori <span class="font-semibold text-indigo-600">"{{ $selectedCategory->name }}"</span>
                        @endif
                        @if(request('search'))
                            untuk pencarian <span class="font-semibold text-indigo-600">"{{ request('search') }}"</span>
                        @endif
                    </h3>
                    {{-- TAMBAHKAN TOMBOL RESET DI SINI --}}
                    @if(request('search') || request('kategori') || (request('sort') && request('sort') != 'latest'))
                        <a href="{{ route('products.catalog') }}" class="mt-2 inline-flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Filter & Pencarian
                        </a>
                    @endif
                </div>

                {{-- Grid Produk --}}
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($products as $product)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 ease-in-out flex flex-col">
                            <a href="{{ route('products.show-public', $product->slug) }}">
                                <div class="aspect-w-1 aspect-h-1 w-full bg-gray-200 rounded-t-lg overflow-hidden">
                                @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-center transition duration-300 ease-in-out transform scale-100 group-hover:scale-105">
                                @else
                                    <img src="https://via.placeholder.com/300x300.png?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-center">
                                @endif
                                </div>
                            </a>
                            <div class="p-4 flex-grow flex flex-col">
                                <h3 class="text-md font-semibold text-gray-800" style="min-height: 2.5rem;"><a href="{{ route('products.show-public', $product->slug) }}" class="hover:text-indigo-600">{{ Str::limit($product->name, 40) }}</a></h3>
                                @if($product->category)<p class="text-xs text-gray-500 mb-2">{{ $product->category->name }}</p>@endif
                                <div class="mt-auto">
                                    <p class="text-lg font-bold text-indigo-600 mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <a href="{{ route('products.show-public', $product->slug) }}" class="block w-full text-center bg-indigo-500 text-white py-2 rounded-md hover:bg-indigo-600 text-sm font-medium">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <p class="text-gray-500 text-center py-10">Tidak ada produk yang ditemukan.</p>
                @endif
            </main>
        </div>
    </div>
</div>
@endsection