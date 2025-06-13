@extends('layouts.public')

@section('title', 'Pangkalan Komputer ID - Solusi Servis & Produk Komputer Anda')

@section('content')
    {{-- ================================================== --}}
    {{--            1. HERO SECTION (DESAIN BARU)          --}}
    {{-- ================================================== --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-gray-900 via-primary-dark to-gray-800 animate-gradient-xy">
        {{-- Elemen Latar Belakang (Opsional) --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gray-900 mix-blend-multiply"></div>
        </div>

        {{-- Konten Utama Hero Section --}}
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <div class="text-center">
                {{-- Kita gunakan style="animation-fill-mode:backwards;" agar elemen tersembunyi sebelum animasi dimulai --}}
                {{-- dan animation-delay untuk membuat animasi muncul berurutan --}}
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl animate-fade-in-up" style="animation-fill-mode: backwards; animation-delay: 0.2s;">
                    Solusi Cerdas, Kinerja Ganas
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-300 animate-fade-in-up" style="animation-fill-mode: backwards; animation-delay: 0.4s;">
                    Temukan layanan servis transparan dan produk IT berkualitas yang siap mendukung semua kebutuhan digital Anda.
                </p>
                <div class="mt-10 max-w-sm mx-auto sm:max-w-none sm:flex sm:justify-center animate-fade-in-up" style="animation-fill-mode: backwards; animation-delay: 0.6s;">
                    <div class="space-y-4 sm:space-y-0 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-5">
                        <a href="{{ route('tracking.form') }}" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark transition-transform transform hover:scale-105">
                            Lacak Servis Anda
                        </a>
                        <a href="{{ route('products.catalog') }}" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-primary bg-indigo-50 hover:bg-indigo-100 transition-transform transform hover:scale-105">
                            Lihat Katalog Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ================================================== --}}
    {{--        2. SEKSI LAYANAN UNGGULAN (Sama seperti sebelumnya) --}}
    {{-- ================================================== --}}
    <div class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Layanan Kami</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Apa yang Bisa Kami Bantu?
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Dari perbaikan ringan hingga perakitan PC custom, tim ahli kami siap membantu Anda.
                </p>
            </div>
            <div class="mt-12">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                    @forelse($featuredServices as $service)
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                                {{-- Placeholder Icon --}}
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.898 20.554L16.5 21.75l-.398-1.196a3.375 3.375 0 00-2.456-2.456L12.75 18l1.196-.398a3.375 3.375 0 002.456-2.456L17.25 14.25l.398 1.196a3.375 3.375 0 002.456 2.456L21 18l-1.196.398a3.375 3.375 0 00-2.456 2.456z" /></svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ $service->name }}</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            {{ Str::limit($service->description, 100) }}
                        </dd>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 col-span-3">Layanan unggulan akan segera ditampilkan.</p>
                    @endforelse
                </dl>
            </div>
        </div>
    </div>


    {{-- ================================================== --}}
    {{--       3. SEKSI PRODUK TERBARU (DESAIN BARU)       --}}
    {{-- ================================================== --}}
    <div class="bg-gray-50">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Produk Terbaru Kami</h2>
                <a href="{{ route('products.catalog') }}" class="whitespace-nowrap text-sm font-medium text-primary hover:text-indigo-500">
                    Lihat Semua Produk <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @forelse ($latestProducts as $product)
                    <div class="group relative">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                            <a href="{{ route('products.show-public', $product->slug) }}">
                                @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                @else
                                    <img src="https://via.placeholder.com/300x400.png?text=Produk" alt="{{ $product->name }}" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                @endif
                            </a>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="{{ route('products.show-public', $product->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ Str::limit($product->name, 35) }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $product->category->name ?? '' }}</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-4">Produk terbaru akan segera ditampilkan.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================================================== --}}
    {{--       4. SEKSI ARTIKEL TERBARU (Sama seperti sebelumnya) --}}
    {{-- ================================================== --}}
    <div class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                 <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Blog & Artikel</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Tips & Trik Terbaru dari Kami
                </p>
            </div>
            @if($latestArticles && $latestArticles->count() > 0)
                <div class="mt-12 mx-auto grid gap-8 lg:grid-cols-3 lg:max-w-none">
                    @foreach ($latestArticles as $article)
                    <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                        <a href="{{ route('articles.show-public', $article->slug) }}">
                            @if ($article->featured_image_path)
                                <img class="h-48 w-full object-cover" src="{{ asset('storage/' . $article->featured_image_path) }}" alt="">
                            @else
                                <div class="h-48 w-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">Gambar Artikel</span>
                                </div>
                            @endif
                        </a>
                        <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-primary">
                                    <a href="#" class="hover:underline">{{ $article->category->name ?? 'Artikel' }}</a>
                                </p>
                                <a href="{{ route('articles.show-public', $article->slug) }}" class="block mt-2">
                                    <p class="text-xl font-semibold text-gray-900">{{ $article->title }}</p>
                                    <p class="mt-3 text-base text-gray-500">{{ $article->excerpt }}</p>
                                </a>
                            </div>
                            <div class="mt-6 flex items-center">
                                <div class="text-sm text-gray-500">
                                    <span>{{ $article->author->name ?? 'Admin' }}</span>
                                    <time datetime="{{ $article->published_at->toDateString() }}" class="ml-2">{{ $article->published_at->translatedFormat('d F Y') }}</time>
                                </div>
                            </div>
                        </div>
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