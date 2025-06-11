@extends('layouts.public')

@section('title', 'Hasil Lacak Servis ' . ($serviceOrder['service_order_number'] ?? '') . ' - Pangkalan Komputer ID')



@section('content')
<head>
    {{-- Jika Anda butuh CSS khusus untuk halaman ini --}}
<link href="{{ asset('vendor/lightbox2/css/lightbox.css') }}" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


</head>
<div class="bg-gray-50 py-12 sm:py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">

        {{-- Header Halaman --}}
        <div class="text-center mb-8">
            <a href="{{ route('tracking.form') }}">
            <img src="{{ asset('images/pkid-logo.png') }}" alt="Logo Pangkalan Komputer ID" class="mx-auto h-16 mb-2">

            </a>
            <h1 class="text-3xl font-bold text-gray-800">Status Servis Anda</h1>
            <p class="text-gray-600 mt-1">Lacak progres perbaikan untuk nomor servis: <span class="font-semibold text-indigo-600">{{ $serviceOrder['service_order_number'] }}</span></p>
            <a href="{{ route('tracking.form') }}" class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-800">&larr; Lacak Nomor Servis Lain</a>
        </div>

        @if (isset($serviceOrder) && !empty($serviceOrder))
        {{-- Kartu Ringkasan Informasi Order --}}
        <div class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h3 class="text-lg font-semibold border-b pb-3 mb-4 text-gray-700">Ringkasan Order</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <div>
                    <span class="font-medium text-gray-500">Pelanggan:</span>
                    <span class="text-gray-800">{{ $serviceOrder['customer_name'] }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-500">Perangkat:</span>
                    <span class="text-gray-800">{{ $serviceOrder['device_type'] }} {{ $serviceOrder['device_brand_model'] ? '('.$serviceOrder['device_brand_model'].')' : '' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-500">Tanggal Diterima:</span>
                    <span class="text-gray-800">{{ $serviceOrder['date_received'] ? \Carbon\Carbon::parse($serviceOrder['date_received'])->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-500">Status Saat Ini:</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $serviceOrder['status'] }}</span>
                </div>
            </div>
            <div class="mt-3 text-sm">
                <span class="font-medium text-gray-500">Keluhan Awal:</span>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $serviceOrder['problem_description_short'] }}</p>
            </div>
        </div>

        {{-- Timeline Progres Perbaikan --}}
        <div>
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Riwayat Progres Perbaikan</h3>
            @if(isset($serviceOrder['updates']) && count($serviceOrder['updates']) > 0)
                <ol class="relative border-l border-gray-300">
                    @foreach($serviceOrder['updates'] as $update)
                    <li class="mb-10 ml-6">
                        {{-- Ikon Timeline --}}
                        <span class="absolute flex items-center justify-center w-6 h-6 bg-indigo-100 rounded-full -left-3 ring-8 ring-white">
                            {{-- Contoh ikon SVG --}}
                            <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd"></path></svg>
                        </span>
                        {{-- Konten Update --}}
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-semibold text-gray-900">{{ $update['update_type'] ?: 'Update Progres' }}</p>
                                <time class="text-xs font-normal text-gray-500">{{ $update['created_at_formatted'] }}</time>
                            </div>
                            <p class="text-xs text-gray-500 mb-2">Oleh: {{ $update['updated_by_name'] }}</p>
                            @if($update['status_from'] && $update['status_to'] && $update['status_from'] !== $update['status_to'])
                                <p class="text-xs text-blue-600 mb-2">Status diubah dari "{{ $update['status_from'] }}" menjadi "{{ $update['status_to'] }}"</p>
                            @endif
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $update['notes'] }}</p>
                            @if(isset($update['photos']) && count($update['photos']) > 0)
                                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    @foreach($update['photos'] as $photo)
                                    <a href="{{ asset('storage/' . $photo['file_path']) }}" data-lightbox="order-{{$serviceOrder['service_order_number']}}-update" data-title="{{ $photo['caption'] ?: $update['notes'] }}">
                                        <img src="{{ asset('storage/' . $photo['file_path']) }}" alt="{{ $photo['caption'] ?: 'Bukti Foto' }}" class="h-24 w-full object-cover rounded-md shadow hover:shadow-lg transition-shadow">
                                    </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ol>
            @else
                <div class="p-4 text-center bg-white rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Belum ada update progres untuk order servis ini.</p>
                </div>
            @endif
        </div>

        @else
            {{-- Ini seharusnya tidak terjadi jika controller sudah redirect dengan error --}}
            <p class="text-center text-red-500">Order servis tidak ditemukan.</p>
        @endif

    </div>
</div>
@endsection

@push('scripts')
    {{-- Jika Anda menggunakan Lightbox, pastikan scriptnya sudah di-load di layout utama --}}
    <script src="{{ asset('vendor/lightbox2/js/lightbox.js') }}"></script>
    <script>
        lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': "Gambar %1 dari %2"
        })
    </script>
@endpush



    