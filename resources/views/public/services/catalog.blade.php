<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Kami - Pangkalan Komputer ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .service-card { border: 1px solid #e2e8f0; border-radius: 0.5rem; background-color: #fff; padding: 1.5rem; }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    {{-- Navigasi Publik Sederhana (Contoh, bisa disamakan dengan katalog produk) --}}
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
                            <a href="{{ route('services.catalog') }}" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium" aria-current="page">Layanan Kami</a>
                            <a href="{{ route('tracking.form') }}" class="text-gray-700 hover:bg-gray-200 hover:text-black px-3 py-2 rounded-md text-sm font-medium">Lacak Servis</a>
                            {{-- Tambahkan link ke Artikel, Kontak nanti --}}
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
            <h1 class="text-3xl font-bold text-gray-800">Layanan Jasa Servis Kami</h1>
            <p class="text-gray-600">Solusi profesional untuk semua kebutuhan perbaikan dan perawatan komputer Anda.</p>
        </header>

        {{-- Filter Kategori Jasa (Bisa ditambahkan nanti) --}}
        {{-- <div class="mb-6"> ... filter ... </div> --}}

        @if($services && $services->count() > 0)
            <div class="space-y-6">
                @foreach ($services as $service)
                <div class="service-card shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out">
                    <h3 class="text-xl font-semibold text-indigo-700 mb-2">
                        <a href="#">{{-- Nanti: route('public.services.show', $service->slug) --}}
                            {{ $service->name }}
                        </a>
                    </h3>
                    @if($service->category)
                    <p class="text-xs text-gray-500 mb-2 italic">Kategori: {{ $service->category->name }}</p>
                    @endif
                    <p class="text-sm text-gray-700 mb-3 whitespace-pre-wrap">{{ Str::limit($service->description, 150) ?: 'Tidak ada deskripsi detail untuk layanan ini.' }}</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mt-auto pt-3 border-t border-gray-200">
                        <div>
                            <span class="font-medium text-gray-600">Estimasi Harga:</span>
                            <span class="text-gray-800">{{ $service->estimated_price ? 'Rp ' . number_format($service->estimated_price, 0, ',', '.') : 'Hubungi Kami' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Estimasi Durasi:</span>
                            <span class="text-gray-800">{{ $service->estimated_duration ?: 'Tergantung Kondisi' }}</span>
                        </div>
                    </div>
                     <div class="mt-4 text-right">
                         <a href="https://wa.me/6281273647463?text=Halo,%20saya%20ingin%20bertanya%20tentang%20layanan%20{{ urlencode($service->name) }}" target="_blank" class="inline-block bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition-colors duration-300 text-sm font-medium">
                            Tanya via WhatsApp
                        </a>
                        {{-- <a href="#" class="inline-block bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 transition-colors duration-300 text-sm font-medium ml-2">
                            Lihat Detail
                        </a> --}}
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $services->links() }} {{-- Menampilkan link paginasi --}}
            </div>
        @else
            <p class="text-gray-500 col-span-full text-center py-10">Belum ada layanan jasa yang tersedia untuk ditampilkan saat ini.</p>
        @endif
    </div>

    <footer class="bg-white mt-10">
        {{-- ... (Footer Anda) ... --}}
    </footer>
</body>
</html>