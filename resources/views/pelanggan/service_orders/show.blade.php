<x-app-layout>
    <x-slot name="title">{{ 'Detail Servis ' . $serviceOrder->service_order_number }}</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Servis Anda') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Nomor Order: {{ $serviceOrder->service_order_number }}</p>
            </div>
            <a href="{{ route('pelanggan.dashboard') }}" class="mt-2 sm:mt-0 text-sm font-medium text-primary hover:text-primary-dark">
                &larr; Kembali ke Riwayat Servis
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses/Error --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Grid Utama --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- Kolom Kiri: Informasi & Aksi --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Kartu Status & Keterangan --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        @php
                            $statusInfo = [
                                'color' => 'bg-gray-100 text-gray-800',
                                'description' => 'Status tidak dikenali.',
                            ];
                            switch ($serviceOrder->status) {
                                case 'Pending':
                                case 'Menunggu Diagnosa':
                                    $statusInfo = ['color' => 'bg-yellow-100 text-yellow-800', 'description' => 'Kami telah menerima perangkat Anda dan akan segera melakukan diagnosa awal.'];
                                    break;
                                case 'In Progress':
                                case 'Diagnosing':
                                case 'Pengujian':
                                    $statusInfo = ['color' => 'bg-blue-100 text-blue-800', 'description' => 'Perangkat Anda sedang dalam proses pengerjaan oleh teknisi ahli kami.'];
                                    break;
                                case 'Menunggu Sparepart':
                                    $statusInfo = ['color' => 'bg-orange-100 text-orange-800', 'description' => 'Kami sedang menunggu komponen pengganti tiba untuk melanjutkan perbaikan.'];
                                    break;
                                case 'Menunggu Persetujuan Pelanggan':
                                    $statusInfo = ['color' => 'bg-orange-100 text-orange-800', 'description' => 'Kami memerlukan persetujuan Anda untuk melanjutkan. Silakan periksa detail penawaran di bawah.'];
                                    break;
                                case 'Completed':
                                    $statusInfo = ['color' => 'bg-green-100 text-green-800', 'description' => 'Kabar baik! Perangkat Anda telah selesai diperbaiki dan siap untuk diambil.'];
                                    break;
                                case 'Picked Up':
                                    $statusInfo = ['color' => 'bg-gray-200 text-gray-800', 'description' => 'Servis telah selesai dan perangkat sudah Anda ambil. Terima kasih!'];
                                    break;
                                case 'Cancelled':
                                case 'Quotation Ditolak':
                                    $statusInfo = ['color' => 'bg-red-100 text-red-800', 'description' => 'Proses servis dibatalkan sesuai permintaan atau kondisi.'];
                                    break;
                            }
                        @endphp
                        <h3 class="text-base font-semibold text-gray-500">Status Terkini</h3>
                        <p class="mt-1 px-4 py-2 text-lg font-bold rounded-full text-center {{ $statusInfo['color'] }}">
                            {{ $serviceOrder->status }}
                        </p>
                        <p class="mt-3 text-sm text-gray-600">{{ $statusInfo['description'] }}</p>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    @if($serviceOrder->quotation_details)
                <section>
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3 text-gray-700">Detail Diagnosa & Penawaran Biaya
                    </h3>
                    <div class="p-4 bg-yellow-50 border border-yellow-300 rounded-md text-sm">
                        <p class="whitespace-pre-wrap">{{ $serviceOrder->quotation_details }}</p>
                        @if($serviceOrder->final_cost)
                        <p class="mt-2 font-semibold">Estimasi/Final Biaya: Rp {{
                            number_format($serviceOrder->final_cost, 0, ',', '.') }}</p>
                        @endif
                    </div>

                    @if($serviceOrder->status == 'Menunggu Persetujuan Pelanggan' &&
                    ($serviceOrder->customer_approval_status == 'Pending' ||
                    is_null($serviceOrder->customer_approval_status)) )
                    
                    @elseif($serviceOrder->customer_approval_status)
                    <p class="mt-3 text-sm">Status Persetujuan Anda: <span class="font-semibold">{{
                            $serviceOrder->customer_approval_status }}</span></p>
                    @endif
                </section>
                @endif
                    </div>

                    {{-- Kartu Aksi Persetujuan Quotation --}}
                    @if($serviceOrder->status == 'Menunggu Persetujuan Pelanggan' && ($serviceOrder->customer_approval_status == 'Pending' || is_null($serviceOrder->customer_approval_status)) )
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-2 border-primary">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Tindakan Diperlukan</h3>
                            <p class="text-sm text-gray-600 mb-4">Silakan setujui atau tolak penawaran untuk melanjutkan proses.</p>
                            <div class="space-y-2">
                                <form action="{{ route('pelanggan.service-orders.respond-quotation', $serviceOrder->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="decision" value="Approved">
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">Setuju</button>
                                </form>
                                <form action="{{ route('pelanggan.service-orders.respond-quotation', $serviceOrder->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="decision" value="Rejected">
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Tolak</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Kartu Aksi Pasca-Servis --}}
                    @if(in_array($serviceOrder->status, ['Completed', 'Picked Up']))
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Layanan Pasca-Servis</h3>
                            <div class="space-y-3">
                                <a href="{{ route('pelanggan.service-orders.download-pdf', $serviceOrder->id) }}" target="_blank" class="w-full block text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">Download Bukti PDF</a>
                                @if(!$existingReview)
                                    <a href="#form-ulasan" onclick="document.getElementById('form-ulasan').scrollIntoView({ behavior: 'smooth' });" class="w-full block text-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg">Beri Ulasan</a>
                                @endif
                                 <a href="{{ route('pelanggan.service-orders.complaints.create', $serviceOrder->id) }}" class="w-full block text-center bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg">Ajukan Komplain</a>
                            </div>
                        </div>
                    @endif

                    {{-- Kartu Detail Perangkat --}}
                     <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                         <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Detail Perangkat</h3>
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div><strong>No. Servis:</strong> {{ $serviceOrder->service_order_number }}</div>
                        <div><strong>Status Saat Ini:</strong> <span
                                class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{
                                $serviceOrder->status }}</span></div>
                        <div><strong>Perangkat:</strong> {{ $serviceOrder->device_type }} {{
                            $serviceOrder->device_brand_model ? '('.$serviceOrder->device_brand_model.')' : '' }}</div>
                        <div><strong>Tgl Diterima:</strong> {{ $serviceOrder->date_received ?
                            $serviceOrder->date_received->format('d M Y, H:i') : '-' }}</div>
                        <div><strong>Teknisi:</strong> {{ $serviceOrder->technician ? $serviceOrder->technician->name :
                            '-' }}</div>
                        <div><strong>Estimasi Selesai:</strong> {{ $serviceOrder->estimated_completion_date ?
                            \Carbon\Carbon::parse($serviceOrder->estimated_completion_date)->format('d M Y') : '-' }}
                        </div>
                        @if($serviceOrder->status === 'Completed' || $serviceOrder->status === 'Picked Up')
                        <div><strong>Tgl Selesai Aktual:</strong> {{ $serviceOrder->date_completed ?
                            $serviceOrder->date_completed->format('d M Y, H:i') : 'Belum Selesai' }}</div>
                        @if($serviceOrder->status === 'Picked Up')
                        <div><strong>Tgl Diambil:</strong> {{ $serviceOrder->date_picked_up ?
                            $serviceOrder->date_picked_up->format('d M Y, H:i') : '-' }}</div>
                        @endif
                        @endif
                    </div>
                    
                     </div>
                     <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                     
                </div>
                    </div>


                {{-- Kolom Kanan: Timeline Riwayat --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-6 border-b pb-3">Riwayat Progres Servis</h3>
                            @if($serviceOrder->updates && $serviceOrder->updates->count() > 0)
                                <ol class="relative border-l-2 border-indigo-200 ml-3">                  
                                    @foreach($serviceOrder->updates as $update)
                                    <li class="mb-10 ml-6">            
                                        <span class="absolute flex items-center justify-center w-6 h-6 bg-primary rounded-full -left-3.5 ring-8 ring-white">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4zM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10zM5 13h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2z"/></svg>
                                        </span>
                                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                            <div class="flex items-center justify-between mb-2">
                                                <p class="text-sm font-semibold text-gray-900">{{ $update->notes }}</p>
                                                <time class="text-xs font-normal text-gray-400">{{ $update->created_at->diffForHumans() }}</time>
                                            </div>
                                            <p class="text-xs text-gray-500 mb-2">Oleh: {{ $update->updatedBy?->name ?? 'Sistem' }}</p>
                                            @if($update->status_from && $update->status_to && $update->status_from !== $update->status_to)
                                                <p class="text-xs text-blue-600">Status diubah menjadi: <span class="font-semibold">{{ $update->status_to }}</span></p>
                                            @endif
                                            @if(isset($serviceOrder->quotation_details) && $update->update_type == 'Hasil Diagnosa') {{-- Contoh penanda khusus --}}
                                            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                                <h4 class="font-semibold text-sm text-yellow-800">Detail Diagnosa & Penawaran</h4>
                                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $serviceOrder->quotation_details }}</p>
                                            </div>
                                            @endif

                                            @if($update->photos && $update->photos->count() > 0)
                                                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2">
                                                    @foreach($update->photos as $photo)
                                                    <a href="{{ asset('storage/' . $photo->file_path) }}" data-lightbox="order-{{$serviceOrder->id}}" data-title="{{ $update->notes }}">
                                                        <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Bukti Foto" class="h-24 w-full object-cover rounded-md shadow hover:shadow-lg transition-shadow">
                                                    </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                    @endforeach
                                </ol>
                            @else
                                <p class="text-sm text-center text-gray-500 py-4">Belum ada update progres untuk order servis ini.</p>
                            @endif
                        </div>
                    </div>
                    {{-- Seksi Ulasan (jika sudah selesai) --}}
                    @if($serviceOrder->warranty)
                <section class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3 text-gray-700">Informasi Garansi Servis</h3>
                    <div class="text-sm space-y-1">
                        <p><strong>Masa Berlaku Garansi:</strong>
                            {{ $serviceOrder->warranty->start_date ?
                            \Carbon\Carbon::parse($serviceOrder->warranty->start_date)->translatedFormat('d F Y') :
                            'N/A' }}
                            s/d
                            {{ $serviceOrder->warranty->end_date ?
                            \Carbon\Carbon::parse($serviceOrder->warranty->end_date)->translatedFormat('d F Y') : 'N/A'
                            }}
                        </p>
                        @if($serviceOrder->warranty->terms)
                        <div>
                            <p class="font-medium">Syarat & Ketentuan Garansi:</p>
                            <p class="whitespace-pre-wrap text-gray-600 bg-gray-50 p-3 rounded-md mt-1">{{
                                $serviceOrder->warranty->terms }}</p>
                        </div>
                        @endif
                        {{-- Anda bisa tambahkan info lain jika ada, misal status garansi (Aktif/Kedaluwarsa)
                        berdasarkan tanggal hari ini --}}
                        @if($serviceOrder->warranty->end_date &&
                        \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($serviceOrder->warranty->end_date)))
                        <p class="text-red-600 font-semibold">Status Garansi: Sudah Kedaluwarsa</p>
                        @elseif($serviceOrder->warranty->start_date &&
                        \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($serviceOrder->warranty->end_date)))
                        <p class="text-green-600 font-semibold">Status Garansi: Aktif</p>
                        @endif
                    </div>
                </section>
                @elseif(in_array($serviceOrder->status, ['Completed', 'Picked Up']))
                {{-- Tampilkan pesan jika servis selesai tapi belum ada info garansi --}}
                <section class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3 text-gray-700">Informasi Garansi Servis</h3>
                    <p class="text-sm text-gray-500">Tidak ada informasi garansi khusus yang tercatat untuk servis ini.
                    </p>
                </section>
                @endif

                @if(in_array($serviceOrder->status, ['Completed', 'Picked Up']))
                <section class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3 text-gray-700">Ulasan & Rating Anda</h3>
                    @if($existingReview)
                    <div class="p-4 bg-green-50 border border-green-300 rounded-md text-sm">
                        <p class="font-semibold">Anda sudah memberikan ulasan untuk servis ini pada {{
                            $existingReview->created_at->format('d M Y') }}:</p>
                        <div class="mt-1">
                            <strong>Rating:</strong>
                            @for($i = 1; $i <= 5; $i++) <span
                                class="{{ $i <= $existingReview->rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                &#9733;</span> {{-- Bintang --}}
                                @endfor
                                ({{ $existingReview->rating }}/5)
                        </div>
                        @if($existingReview->comment)
                        <p class="mt-1"><strong>Komentar:</strong></p>
                        <p class="whitespace-pre-wrap text-gray-600">{{ $existingReview->comment }}</p>
                        @endif
                    </div>
                    @else
                    <form action="{{ route('pelanggan.service-orders.reviews.store', $serviceOrder->id) }}"
                        method="POST"> {{-- Rute ini akan kita buat --}}
                        @csrf
                        <div class="mb-4">
                            <label for="rating" class="block text-sm font-medium text-gray-700">Rating (1-5
                                Bintang)</label>
                            <select name="rating" id="rating" class="mt-1 block w-full sm:w-1/3" required>
                                <option value="">Pilih Rating</option>
                                <option value="5" {{ old('rating')==5 ? 'selected' : '' }}>★★★★★ (Sangat Puas)</option>
                                <option value="4" {{ old('rating')==4 ? 'selected' : '' }}>★★★★☆ (Puas)</option>
                                <option value="3" {{ old('rating')==3 ? 'selected' : '' }}>★★★☆☆ (Cukup)</option>
                                <option value="2" {{ old('rating')==2 ? 'selected' : '' }}>★★☆☆☆ (Kurang Puas)</option>
                                <option value="1" {{ old('rating')==1 ? 'selected' : '' }}>★☆☆☆☆ (Sangat Tidak Puas)
                                </option>
                            </select>
                            @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700">Komentar Ulasan
                                (Opsional)</label>
                            <textarea name="comment" id="comment" rows="4"
                                class="mt-1 block w-full">{{ old('comment') }}</textarea>
                            @error('comment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <button type="submit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                                Kirim Ulasan
                            </button>
                        </div>
                    </form>
                    @endif
                </section>
                @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
@endpush