@extends('layouts.public')

@section('title', $service->name . ' - Pangkalan Komputer ID')

@section('content')
<div class="bg-white py-12 sm:py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb Navigation --}}
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol role="list" class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('services.catalog') }}" class="text-gray-500 hover:text-gray-700">Layanan</a></li>
                @if($service->category)
                <li><div class="flex items-center"><svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" /></svg><a href="{{ route('services.catalog', ['kategori' => $service->category->slug]) }}" class="ml-2 text-gray-500 hover:text-gray-700">{{ $service->category->name }}</a></div></li>
                @endif
            </ol>
        </nav>

        <div class="lg:text-center">
            <h2 class="text-base font-semibold text-primary tracking-wide uppercase">Layanan Jasa</h2>
            <p class="mt-2 text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">{{ $service->name }}</p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">{{ $service->description ?: 'Deskripsi detail untuk layanan ini akan segera tersedia.' }}</p>
        </div>

        <div class="mt-12 max-w-lg mx-auto lg:max-w-none lg:grid lg:grid-cols-2 lg:gap-8">
            {{-- Kolom Kiri: Detail Harga & Durasi --}}
            <div class="bg-gray-50 p-8 rounded-lg shadow-sm">
                 <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Layanan</h3>
                 <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Estimasi Harga</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $service->estimated_price ? 'Rp ' . number_format($service->estimated_price, 0, ',', '.') : 'Hubungi Kami' }}</dd>
                    </div>
                     <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Estimasi Durasi</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $service->estimated_duration ?: 'Tergantung Kondisi' }}</dd>
                    </div>
                     <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Kategori</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $service->category->name ?? 'Umum' }}</dd>
                    </div>
                 </dl>
            </div>

             {{-- Kolom Kanan: Call to Action --}}
            <div class="mt-8 lg:mt-0 bg-indigo-50 p-8 rounded-lg shadow-sm flex flex-col justify-center items-center text-center">
                <h3 class="text-lg font-semibold text-gray-900">Punya Pertanyaan?</h3>
                <p class="mt-2 text-sm text-gray-600">Diskusikan kebutuhan servis Anda dengan tim ahli kami sekarang juga.</p>
                <a href="https://wa.me/6281273647463?text=Halo,%20saya%20ingin%20bertanya%20tentang%20layanan%20{{ urlencode($service->name) }}" target="_blank" class="mt-6 inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-8 py-3 text-base font-medium text-white hover:bg-green-700">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.358 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                    </svg>
                    Tanya via WhatsApp
                </a>
            </div>
        </div>
         <div class="mt-8 text-center">
            <a href="{{ route('services.catalog') }}" class="text-sm text-primary hover:text-primary-dark">&larr; Kembali ke Daftar Layanan</a>
        </div>
    </div>
</div>
@endsection