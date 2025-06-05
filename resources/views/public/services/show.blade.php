<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Pangkalan Komputer ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Jika Anda menggunakan Lightbox untuk galeri gambar produk detail --}}
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" /> --}}
    <style>
        /* Style tambahan jika perlu */
    </style>
</head>
<body class="bg-gray-100 font-sans">
    {{-- Navigasi Publik (bisa disamakan dengan catalog.blade.php atau dibuat komponen terpisah) --}}
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex-shrink-0">
                        <img class="h-10 w-auto" src="{{ asset('images/logo_pangkalan_komputer.png') }}" alt="Pangkalan Komputer ID">
                    </a>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ url('/') }}" class="text-gray-700 hover:bg-gray-200 hover:text-black px-3 py-2 rounded-md text-sm font-medium">Beranda</a>
                            <a href="{{ route('products.catalog') }}" class="text-gray-700 hover:bg-gray-200 hover:text-black px-3 py-2 rounded-md text-sm font-medium">Produk</a>
                            <a href="{{ route('tracking.form') }}" class="text-gray-700 hover:bg-gray-200 hover:text-black px-3 py-2 rounded-md text-sm font-medium">Lacak Servis</a>
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
        <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-lg overflow-hidden md:flex">
            <div class="md:flex-shrink-0 md:w-1/2">
                @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                    <img class="h-64 w-full object-cover md:h-full md:object-contain" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                @else
                    <img class="h-64 w-full object-cover md:h-full md:object-contain" src="https://via.placeholder.com/600x400.png?text={{ urlencode($product->name) }}" alt="{{ $product->name }}">
                @endif
            </div>
            <div class="p-6 md:p-8 md:w-1/2">
                @if($product->category)
                <div class="uppercase tracking-wide text-sm text-indigo-600 font-semibold">{{ $product->category->name }}</div>
                @endif
                <h1 class="block mt-1 text-3xl leading-tight font-bold text-black hover:underline">{{ $product->name }}</h1>
                <p class="mt-4 text-2xl text-gray-800 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                <div class="mt-4">
                    <span class="text-gray-600 text-sm">Stok: </span>
                    @if($product->stock_quantity > 0)
                        <span class="text-green-600 font-semibold">{{ $product->stock_quantity }} unit tersedia</span>
                    @else
                        <span class="text-red-600 font-semibold">Stok Habis</span>
                    @endif
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900">Deskripsi Produk</h3>
                    <p class="mt-2 text-gray-600 text-sm whitespace-pre-wrap">
                        {{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>

                <div class="mt-6">
                    {{-- Tombol Aksi (misal: Tambah ke Keranjang jika ada fitur e-commerce, atau Kontak via WA) --}}
                    <a href="https://wa.me/6281273647463?text=Halo,%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}" target="_blank" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-3 md:text-lg md:px-10">
                        Tanya via WhatsApp
                    </a>
                </div>
                 <div class="mt-4 text-center">
                    <a href="{{ route('products.catalog') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Kembali ke Katalog Produk</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white mt-10">
        {{-- ... (Footer Anda) ... --}}
    </footer>
    {{-- Jika menggunakan Lightbox --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script> if(typeof lightbox !== 'undefined') { lightbox.option({ /* options */ }); } </script> --}}
</body>
</html>