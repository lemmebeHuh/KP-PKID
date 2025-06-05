<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Lacak Servis {{ $serviceOrder['service_order_number'] ?? '' }} - Pangkalan Komputer ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Tambahkan CSS Lightbox2 dari CDN --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto mt-10 mb-10 p-4 max-w-3xl">
        <div class="text-center mb-6">
            <a href="{{ route('tracking.form') }}">
                <img src="{{ asset('images/logo_pangkalan_komputer.png') }}" alt="Logo Pangkalan Komputer ID" class="mx-auto h-16 mb-2">
            </a>
            <h1 class="text-2xl font-semibold text-gray-700">Status Servis: {{ $serviceOrder['service_order_number'] }}</h1>
            <a href="{{ route('tracking.form') }}" class="text-sm text-indigo-600 hover:text-indigo-900">&larr; Lacak Nomor Servis Lain</a>
        </div>

        @if (isset($serviceOrder) && !empty($serviceOrder))
        <div class="bg-white p-6 sm:p-8 rounded-lg shadow-lg space-y-6">
            {{-- Informasi Utama Order --}}
            <section>
                <h3 class="text-lg font-semibold border-b pb-2 mb-3 text-gray-700">Informasi Order</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                    <div><strong>Pelanggan:</strong> {{ $serviceOrder['customer_name'] }}</div>
                    <div><strong>Status Saat Ini:</strong> <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $serviceOrder['status'] }}</span></div>
                    <div><strong>Perangkat:</strong> {{ $serviceOrder['device_type'] }} {{ $serviceOrder['device_brand_model'] ? '('.$serviceOrder['device_brand_model'].')' : '' }}</div>
                    <div><strong>Tgl Diterima:</strong> {{ $serviceOrder['date_received'] ? \Carbon\Carbon::parse($serviceOrder['date_received'])->translatedFormat('d M Y, H:i') : '-' }}</div>
                    <div><strong>Estimasi Selesai:</strong> {{ $serviceOrder['estimated_completion_date'] ? \Carbon\Carbon::parse($serviceOrder['estimated_completion_date'])->translatedFormat('d M Y') : '-' }}</div>
                    @if($serviceOrder['status'] === 'Completed' || $serviceOrder['status'] === 'Picked Up')
                        <div><strong>Tgl Selesai Aktual:</strong> {{ $serviceOrder['date_completed'] ? \Carbon\Carbon::parse($serviceOrder['date_completed'])->translatedFormat('d M Y, H:i') : 'Belum Selesai' }}</div>
                        @if($serviceOrder['status'] === 'Picked Up')
                        <div><strong>Tgl Diambil:</strong> {{ $serviceOrder['date_picked_up'] ? \Carbon\Carbon::parse($serviceOrder['date_picked_up'])->translatedFormat('d M Y, H:i') : '-' }}</div>
                        @endif
                    @endif
                </div>
                <div class="mt-2 text-sm">
                    <strong>Keluhan Awal:</strong> <p class="text-gray-600 whitespace-pre-wrap">{{ $serviceOrder['problem_description_short'] }}</p>
                </div>
            </section>

            {{-- Riwayat Update Progres --}}
            <section>
                <h3 class="text-lg font-semibold border-b pb-2 mb-3 text-gray-700">Riwayat Progres Perbaikan</h3>
                @if(isset($serviceOrder['updates']) && count($serviceOrder['updates']) > 0)
                    <div class="space-y-4">
                        @foreach($serviceOrder['updates'] as $update)
                        <div class="border rounded-md p-3 text-sm bg-gray-50">
                            <p class="font-semibold">{{ $update['update_type'] ?: 'Update' }} - <span class="font-normal text-gray-600">{{ $update['created_at_formatted'] }}</span></p>
                            <p class="text-xs text-gray-500">Oleh: {{ $update['updated_by_name'] }}</p>
                            @if($update['status_from'] || $update['status_to'])
                                @if ($update['status_from'] !== $update['status_to']) {{-- Hanya tampilkan jika ada perubahan status --}}
                                <p class="text-xs text-blue-600">Status: {{ $update['status_from'] ?: 'N/A' }} &rarr; {{ $update['status_to'] ?: 'N/A' }}</p>
                                @endif
                            @endif
                            <p class="mt-1 whitespace-pre-wrap">{{ $update['notes'] }}</p>
                            @if(isset($update['photos']) && count($update['photos']) > 0)
                                <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                    @foreach($update['photos'] as $photo)
                                    <a href="{{ asset('storage/' . $photo['file_path']) }}" data-lightbox="order-{{$serviceOrder['service_order_number']}}-update" data-title="{{ $photo['caption'] ?: $update['notes'] }}">
                                        <img src="{{ asset('storage/' . $photo['file_path']) }}" alt="{{ $photo['caption'] ?: 'Bukti Foto' }}" class="h-20 w-full object-cover rounded shadow hover:shadow-md transition-shadow">
                                    </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Belum ada update progres untuk order servis ini.</p>
                @endif
            </section>

            {{-- Informasi Garansi jika ada --}}
             @if($serviceOrder['warranty'])
                <section>
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3 text-gray-700">Informasi Garansi</h3>
                    <div class="text-sm">
                        <p><strong>Masa Berlaku:</strong> {{ $serviceOrder['warranty']['start_date_formatted'] }} s/d {{ $serviceOrder['warranty']['end_date_formatted'] }}</p>
                        @if($serviceOrder['warranty']['terms'])
                        <p class="mt-1"><strong>Syarat & Ketentuan:</strong></p>
                        <p class="whitespace-pre-wrap text-gray-600">{{ $serviceOrder['warranty']['terms'] }}</p>
                        @endif
                    </div>
                </section>
            @endif

        </div>
        <p class="text-center text-sm text-gray-500 mt-6">&copy; {{ date('Y') }} Pangkalan Komputer ID</p>
        @else
            {{-- Ini seharusnya tidak terjadi jika controller sudah redirect dengan error --}}
            <p class="text-center text-red-500">Order servis tidak ditemukan.</p>
        @endif
    </div>
    {{-- Tambahkan JS Lightbox2 dari CDN (setelah JS proyek Anda jika ada) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script>
        // Opsi konfigurasi Lightbox2 (opsional)
        lightbox.option({
          'resizeDuration': 200,
          'wrapAround': true, // Jika ada banyak gambar, bisa kembali ke awal setelah gambar terakhir
          'albumLabel': "Gambar %1 dari %2" // Untuk melokalisasi label jika perlu
        })
    </script>
</body>
</html>