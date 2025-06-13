@extends('layouts.public')

@section('title', $product->name . ' - Pangkalan Komputer ID')

@section('content')
<div class="bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Breadcrumb Navigation --}}
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol role="list" class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('products.catalog') }}" class="text-gray-500 hover:text-gray-700">Produk</a></li>
                @if($product->category)
                <li><div class="flex items-center"><svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" /></svg><a href="{{ route('products.catalog', ['kategori' => $product->category->slug]) }}" class="ml-2 text-gray-500 hover:text-gray-700">{{ $product->category->name }}</a></div></li>
                @endif
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            {{-- Kolom Kiri: Galeri Gambar --}}
            <div>
                {{-- Gambar Utama --}}
                <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-100 border">
                    @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-contain object-center">
                    @else
                        <img src="https://via.placeholder.com/600x600.png?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="h-full w-full object-contain object-center">
                    @endif
                </div>
                {{-- Thumbnail (jika ada lebih dari 1 gambar nanti) --}}
                <div class="mt-4 grid grid-cols-5 gap-4">
                    {{-- Contoh Thumbnail Aktif --}}
                    <div class="aspect-w-1 aspect-h-1 w-full rounded-md border-2 border-primary overflow-hidden">
                        @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-contain object-center">
                        @else
                            <img src="https://via.placeholder.com/100x100.png?text=1" alt="{{ $product->name }}" class="h-full w-full object-contain object-center">
                        @endif
                    </div>
                    {{-- Placeholder untuk thumbnail lainnya nanti --}}
                </div>
            </div>

            {{-- Kolom Kanan: Informasi & Aksi --}}
            <div class="flex flex-col">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{ $product->name }}</h1>

                <div class="mt-3">
                    <p class="text-3xl tracking-tight text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>

                {{-- Rating (placeholder) --}}
                <div class="mt-4">
                    <div class="flex items-center">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.868 2.884c.321-.662 1.134-.662 1.456 0l1.96 4.048 4.472.65a.75.75 0 01.416 1.279l-3.236 3.152.764 4.456a.75.75 0 01-1.088.791L12 15.347l-4.006 2.105a.75.75 0 01-1.088-.79l.764-4.456-3.236-3.152a.75.75 0 01.416-1.28l4.472-.65 1.96-4.048z" clip-rule="evenodd" /></svg>
                        </div>
                        <p class="ml-2 text-sm text-gray-500">Belum ada ulasan</p>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex items-center">
                        @if($product->stock_quantity > 0)
                            <svg class="h-5 w-5 flex-shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg>
                            <p class="ml-2 text-sm font-medium text-gray-700">Stok Tersedia ({{ $product->stock_quantity }} unit)</p>
                        @else
                            <svg class="h-5 w-5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                            <p class="ml-2 text-sm font-medium text-gray-700">Stok Habis</p>
                        @endif
                    </div>
                </div>

                <div class="mt-8">
                    <a href="https://wa.me/6283192310040?text=Halo,%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}" target="_blank" class="flex w-full items-center justify-center rounded-md border border-transparent bg-green-600 px-8 py-3 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg style="margin-right: 5px" xmlns="http://www.w3.org/2000/svg" width="30"  fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                        </svg>
                        Tanya via WhatsApp
                    </a>
                </div>

                {{-- Deskripsi Detail dengan Tab --}}
                <div x-data="{ tab: 'deskripsi' }" class="mt-10">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button @click="tab = 'deskripsi'" :class="{ 'border-primary text-primary': tab === 'deskripsi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'deskripsi' }" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                                Deskripsi
                            </button>
                            <button @click="tab = 'spesifikasi'" :class="{ 'border-primary text-primary': tab === 'spesifikasi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'spesifikasi' }" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                                Spesifikasi
                            </button>
                        </nav>
                    </div>
                    <div class="mt-6 text-sm text-gray-600 space-y-6">
                        <div x-show="tab === 'deskripsi'" class="whitespace-pre-wrap">
                            <p>{{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}</p>
                        </div>
                        <div x-show="tab === 'spesifikasi'">
                            <p>Informasi spesifikasi teknis akan ditampilkan di sini.</p>
                            {{-- Contoh:
                            <ul>
                                <li><strong>Prosesor:</strong> Intel Core i7-12700H</li>
                                <li><strong>RAM:</strong> 16GB DDR5 4800MHz</li>
                            </ul> 
                            --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Produk Terkait (kode sudah ada sebelumnya) --}}
        @if($relatedProducts && $relatedProducts->count() > 0)
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Produk Lainnya yang Mungkin Anda Suka</h2>
            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach($relatedProducts as $related)
                <div class="group relative">
                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                        <a href="{{ route('products.show-public', $related->slug) }}"><img src="{{ $related->image_path ? asset('storage/' . $related->image_path) : 'https://via.placeholder.com/300x400.png?text=Produk' }}" alt="{{ $related->name }}" class="h-full w-full object-cover object-center lg:h-full lg:w-full"></a>
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700"><a href="{{ route('products.show-public', $related->slug) }}"><span aria-hidden="true" class="absolute inset-0"></span>{{ Str::limit($related->name, 25) }}</a></h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $related->category->name ?? '' }}</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection


