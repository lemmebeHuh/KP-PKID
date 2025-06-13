@extends('layouts.public') {{-- Menggunakan layout publik kita --}}

@section('title', 'Pangkalan Komputer ID - Solusi Servis & Produk Komputer Anda')

@section('content')
    {{-- 1. Hero Section --}}
    <div class="relative bg-gray-800 text-white overflow-hidden">
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="{{ asset('images/TK20_1600x1200_MTBall.jpg') }}" alt="Teknisi Komputer">
        </div>
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-gray-800 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Solusi Lengkap</span>
                            <span class="block text-primary-400 xl:inline">Servis & Produk IT</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Pangkalan Komputer ID menyediakan layanan perbaikan profesional dan transparan, serta berbagai produk dan aksesoris komputer berkualitas untuk semua kebutuhan Anda.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('tracking.form') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary-dark md:py-4 md:text-lg md:px-10">
                                    Lacak Servis Anda
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="{{ route('services.catalog') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
                                    Lihat Layanan
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        
    </div>

    {{-- 2. Seksi Layanan Unggulan --}}
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Layanan Kami</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Apa yang Bisa Kami Bantu?
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Dari perbaikan ringan hingga perakitan PC custom, tim ahli kami siap membantu Anda.
                </p>
            </div>
            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                    @foreach($featuredServices as $service)
                    <div class="relative">
                        <dt>
                            {{-- SVG Icon Placeholder --}}
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 16v-2m0-8v-2m0 8v-2m-4-2H4m16 0h-2m-8-4H4m16 0h-2m-4-2V4m0 16v-2m0-8v-2" /></svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ $service->name }}</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            {{ Str::limit($service->description, 100) }}
                        </dd>
                    </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </div>

    {{-- 3. Seksi Produk Terbaru --}}
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                 <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Katalog Produk</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Produk Terbaru Kami
                </p>
            </div>
            @if($latestProducts && $latestProducts->count() > 0)
                {{-- Menggunakan kode yang sama dari Halaman Katalog Produk --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                     @foreach ($latestProducts as $product)
                     <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out flex flex-col">
                         <a href="{{ route('products.show-public', $product->slug) }}">
                             @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                                 <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-lg">
                             @else
                                 <img src="https://via.placeholder.com/300x200.png?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-lg">
                             @endif
                         </a>
                         <div class="p-4 flex-grow flex flex-col">
                             <h3 class="text-md font-semibold text-gray-800" style="min-height: 2.5rem;">
                                 <a href="{{ route('products.show-public', $product->slug) }}" class="hover:text-primary">{{ Str::limit($product->name, 40) }}</a>
                             </h3>
                             <div class="mt-auto">
                                 <p class="text-lg font-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                             </div>
                         </div>
                     </div>
                     @endforeach
                </div>
                <div class="text-center mt-8">
                    <a href="{{ route('products.catalog') }}" class="text-primary hover:text-primary-dark font-semibold">Lihat Semua Produk &rarr;</a>
                </div>
            @else
                <p class="text-center text-gray-500">Produk akan segera tersedia.</p>
            @endif
        </div>
    </div>

    {{-- 4. Seksi Artikel Terbaru --}}
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                 <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Blog & Artikel</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Tips & Trik Terbaru dari Kami
                </p>
            </div>
            @if($latestArticles && $latestArticles->count() > 0)
                {{-- Menggunakan kode yang sama dari Halaman Daftar Artikel --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($latestArticles as $article)
                    <div class="bg-gray-50 rounded-lg overflow-hidden flex flex-col hover:shadow-lg transition-shadow">
                        <a href="{{ route('articles.show-public', $article->slug) }}" class="block p-6">
                            @if($article->category)
                            <p class="text-xs text-primary font-semibold uppercase">{{ $article->category->name }}</p>
                            @endif
                            <h3 class="mt-1 text-lg font-semibold text-gray-800 hover:text-primary-dark">{{ $article->title }}</h3>
                            <p class="mt-2 text-gray-600 text-sm">{{ $article->excerpt }}</p>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-8">
                    <a href="{{ route('articles.index-public') }}" class="text-primary hover:text-primary-dark font-semibold">Lihat Semua Artikel &rarr;</a>
                </div>
            @else
                 <p class="text-center text-gray-500">Artikel akan segera tersedia.</p>
            @endif
        </div>
    </div>
@endsection