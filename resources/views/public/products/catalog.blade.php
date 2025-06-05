<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Pangkalan Komputer ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .product-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background-color: #fff;
            display: flex;
            flex-direction: column;
        }

        .product-card img {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .product-card-content {
            padding: 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-card-content h3 {
            min-height: 3em;
        }

        /* Untuk tinggi nama produk yang konsisten */
    </style>
</head>

<body class="bg-gray-100 font-sans">
    {{-- Navigasi Publik Sederhana (Contoh) --}}
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex-shrink-0">
                        <img class="h-10 w-auto" src="{{ asset('images/logo_pangkalan_komputer.png') }}"
                            alt="Pangkalan Komputer ID">
                    </a>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ url('/') }}"
                                class="text-gray-700 hover:bg-gray-200 hover:text-black px-3 py-2 rounded-md text-sm font-medium">Beranda</a>
                            <a href="{{ route('products.catalog') }}"
                                class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium"
                                aria-current="page">Produk</a>
                            <a href="{{ route('tracking.form') }}"
                                class="text-gray-700 hover:bg-gray-200 hover:text-black px-3 py-2 rounded-md text-sm font-medium">Lacak
                                Servis</a>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    {{-- ... (Tombol Login/Register/Dashboard) ... --}}
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-6 p-4">
        <header class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-800">Katalog Produk Kami</h1>
            <p class="text-gray-600">Temukan berbagai produk kebutuhan komputer Anda.</p>
        </header>

        {{-- Filter Kategori (Bisa ditambahkan nanti) --}}
        {{-- <div class="mb-6"> ... filter ... </div> --}}

        @if($products && $products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
            <div
                class="product-card shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out flex flex-col">
                <a href="#"> {{-- Nanti: route('public.products.show', $product->slug) --}}
                    @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                        class="w-full h-56 object-cover"> {{-- h-56 atau sesuaikan --}}
                    @else
                    <img src="https://via.placeholder.com/300x200.png?text={{ urlencode($product->name) }}"
                        alt="{{ $product->name }}" class="w-full h-56 object-cover">
                    @endif
                </a>
                <div class="product-card-content p-4 flex-grow flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">
                        <a href="#" class="hover:text-indigo-600">{{ Str::limit($product->name, 45) }}</a> {{-- Batasi
                        panjang judul --}}
                    </h3>
                    @if($product->category)
                    <p class="text-xs text-gray-500 mb-2">{{ $product->category->name }}</p>
                    @endif
                    {{-- Deskripsi singkat bisa ditambahkan jika perlu --}}
                    {{-- <p class="text-sm text-gray-600 mb-3 flex-grow">{{ Str::limit($product->description, 60) }}</p> --}}

                    <div class="mt-auto">
                        <p class="text-xl font-bold text-indigo-600 mb-3">Rp {{ number_format($product->price, 0, ',',
                            '.') }}</p>
                        <a href="{{ route('products.show-public', $product->slug) }}"
                            class="block w-full text-center bg-indigo-500 ...">Lihat Detail</a>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1"><a
                                href="{{ route('products.show-public', $product->slug) }}"
                                class="hover:text-indigo-600">{{ Str::limit($product->name, 45) }}</a></h3>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }} {{-- Menampilkan link paginasi --}}
        </div>
        @else
        <p class="text-gray-500 col-span-full text-center py-10">Belum ada produk yang tersedia saat ini.</p>
        @endif
    </div>

    <footer class="bg-white mt-10">
        {{-- ... (Footer Anda) ... --}}
    </footer>
</body>

</html>