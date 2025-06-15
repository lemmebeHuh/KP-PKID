<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Order Servis Anda: ') }} {{ $serviceOrder->service_order_number }}
            </h2>
            <a href="{{ route('pelanggan.dashboard') }}" class="text-sm text-primary hover:text-indigo-900">
                &larr; Kembali ke Riwayat Servis
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
            @endif --}}

            <div class="bg-white p-6 sm:p-8 rounded-lg shadow-lg space-y-6">
                {{-- Informasi Utama Order (Sama seperti halaman publik/admin show, bisa disesuaikan) --}}
                <section>
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3 text-gray-700">Informasi Order</h3>
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
                    <div class="mt-2 text-sm">
                        <strong>Keluhan Awal:</strong>
                        <p class="text-gray-600 whitespace-pre-wrap">{{ $serviceOrder->problem_description }}</p>
                    </div>
                    <div class="mt-2 text-sm">
                        <strong>Kelengkapan Diterima:</strong>
                        <p class="text-gray-600 whitespace-pre-wrap">{{ $serviceOrder->accessories_received ?: '-' }}
                        </p>
                    </div>
                </section>

                {{-- Detail Diagnosa & Quotation --}}
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

                    {{-- Form Persetujuan/Penolakan akan ada di sini --}}
                    @if($serviceOrder->status == 'Menunggu Persetujuan Pelanggan' &&
                    ($serviceOrder->customer_approval_status == 'Pending' ||
                    is_null($serviceOrder->customer_approval_status)) )
                    <div class="mt-4 pt-4 border-t">
                        <h4 class="font-semibold mb-2">Tindakan Anda:</h4>
                        <p class="text-sm mb-3">Silakan setujui atau tolak penawaran biaya dan pekerjaan yang diusulkan
                            di atas.</p>

                        {{-- Form untuk SETUJU --}}
                        <form action="{{ route('pelanggan.service-orders.respond-quotation', $serviceOrder->id) }}"
                            method="POST" class="inline-block mr-2">
                            @csrf
                            <input type="hidden" name="decision" value="Approved">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Setuju dengan Penawaran
                            </button>
                        </form>

                        {{-- Form untuk TOLAK --}}
                        <form action="{{ route('pelanggan.service-orders.respond-quotation', $serviceOrder->id) }}"
                            method="POST" class="inline-block mt-2 sm:mt-0">
                            @csrf
                            <input type="hidden" name="decision" value="Rejected">
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Tolak Penawaran
                            </button>
                        </form>
                    </div>
                    @elseif($serviceOrder->customer_approval_status)
                    <p class="mt-3 text-sm">Status Persetujuan Anda: <span class="font-semibold">{{
                            $serviceOrder->customer_approval_status }}</span></p>
                    @endif
                </section>
                @endif

                {{-- Riwayat Update Progres (Sama seperti halaman publik/admin show) --}}
                <section>
                    <h3 class="text-lg font-semibold border-b pb-2 mb-3 text-gray-700">Riwayat Progres Perbaikan</h3>
                    @if($serviceOrder->updates && $serviceOrder->updates->count() > 0)
                    <div class="space-y-4">
                        @foreach($serviceOrder->updates as $update)
                        <div class="border rounded-md p-3 text-sm bg-gray-50">
                            <p class="font-semibold">{{ $update->update_type ?: 'Update' }} - <span
                                    class="font-normal text-gray-600">{{ $update->created_at->format('d M Y, H:i')
                                    }}</span></p>
                            @if($update->updatedBy)
                            <p class="text-xs text-gray-500">Oleh: {{ $update->updatedBy->name }}</p>
                            @endif
                            @if($update->status_from || $update->status_to)
                            @if ($update->status_from !== $update->status_to)
                            <p class="text-xs text-blue-600">Status: {{ $update->status_from ?: 'N/A' }} &rarr; {{
                                $update->status_to ?: 'N/A' }}</p>
                            @endif
                            @endif
                            <p class="mt-1 whitespace-pre-wrap">{{ $update->notes }}</p>
                            @if($update->photos && $update->photos->count() > 0)
                            <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                @foreach($update->photos as $photo)
                                <a href="{{ asset('storage/' . $photo->file_path) }}"
                                    data-lightbox="order-{{$serviceOrder->service_order_number}}-update-{{$update->id}}"
                                    data-title="{{ $photo->caption ?: $update->notes }}">
                                    <img src="{{ asset('storage/' . $photo->file_path) }}"
                                        alt="{{ $photo->caption ?: 'Bukti Foto' }}"
                                        class="h-20 w-full object-cover rounded shadow hover:shadow-md transition-shadow">
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

                {{-- Informasi Garansi jika ada (Sama seperti halaman publik/admin show) --}}
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

                {{-- Tombol Aksi Lain untuk Pelanggan (Download PDF, Review - NANTI) --}}
                @if($serviceOrder->status == 'Completed' || $serviceOrder->status == 'Picked Up')
                <div class="mt-6 pt-4 border-t">
                    <h4 class="font-semibold mb-2">Aksi Lainnya:</h4>
                    <a href="{{ route('pelanggan.service-orders.download-pdf', $serviceOrder->id) }}" target="_blank"
                        {{-- Buka di tab baru agar tidak meninggalkan halaman --}}
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2 text-sm">
                        Download Bukti Servis (PDF)
                    </a>
                    {{-- Tombol Beri Review (Nanti) --}}
                    {{-- <a href="#"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm">Beri
                        Ulasan
                    </a> --}}

                    {{-- Tombol/Link Ajukan Komplain --}}
                    <a href="{{ route('pelanggan.service-orders.complaints.create', $serviceOrder->id) }}" {{-- Rute ini
                        akan kita buat --}}
                        class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm">
                        Ajukan Komplain
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>