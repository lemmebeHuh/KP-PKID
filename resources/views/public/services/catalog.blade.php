@extends('layouts.public')

@section('title', 'Layanan Kami - Pangkalan Komputer ID')

@section('content')
<div class="container mx-auto mt-6 p-4">
    <header class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Layanan Jasa Servis Kami</h1>
        <p class="text-gray-600">Solusi profesional untuk semua kebutuhan perbaikan dan perawatan komputer Anda.</p>
    </header>

    @if($services && $services->count() > 0)
        <div class="space-y-6">
            @foreach ($services as $service)
            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out p-6">
                <h3 class="text-xl font-semibold text-indigo-700 mb-2">
                    <a href="#">{{-- Nanti: route('services.show-public', $service->slug) --}}
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
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $services->links() }}
        </div>
    @else
        <p class="text-gray-500 col-span-full text-center py-10">Belum ada layanan jasa yang tersedia untuk ditampilkan saat ini.</p>
    @endif
</div>
@endsection