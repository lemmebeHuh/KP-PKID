<x-app-layout>
    <x-slot name="title">{{ 'Detail Order ' . $serviceOrder->service_order_number }}</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Order Servis') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Nomor Order: <span class="font-mono font-bold text-primary">{{ $serviceOrder->service_order_number }}</span>
                </p>
            </div>
            <div class="mt-2 sm:mt-0 flex space-x-2">
                <a href="{{ route('admin.service-orders.index') }}" class="text-sm font-medium text-gray-600 hover:text-primary py-2 px-4">
                    &larr; Kembali
                </a>
                <a href="{{ route('admin.service-orders.edit', $serviceOrder->id) }}" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg text-sm inline-flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Order
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- KOLOM KIRI --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Panel Detail Utama --}}
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Ringkasan Order</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div>
                                <p class="text-gray-500">Pelanggan</p>
                                <p class="font-semibold text-gray-800">{{ $serviceOrder->customer?->name ?? 'N/A' }}</p>
                                <p class="text-gray-600">{{ $serviceOrder->customer?->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Teknisi</p>
                                <p class="font-semibold text-gray-800">{{ $serviceOrder->technician?->name ?? 'Belum Ditugaskan' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Perangkat</p>
                                <p class="font-semibold text-gray-800">{{ $serviceOrder->device_type }} ({{ $serviceOrder->device_brand_model ?: '-' }})</p>
                            </div>
                             <div>
                                <p class="text-gray-500">Keluhan Awal</p>
                                <p class="font-semibold text-gray-800">{{ $serviceOrder->problem_description }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Panel Riwayat Progres (Bukan Timeline) --}}
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Riwayat Progres Servis</h3>
                        </div>
                        <div class="p-2">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($serviceOrder->updates as $update)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap w-40">
                                                <p class="text-sm font-medium text-gray-900">{{ $update->created_at->translatedFormat('d M Y') }}</p>
                                                <p class="text-xs text-gray-500">{{ $update->created_at->translatedFormat('H:i') }}</p>
                                            </td>
                                            <td class="px-4 py-3">
                                                <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $update->notes }}</p>
                                                <p class="text-xs text-gray-500 mt-1">Oleh: {{ $update->updatedBy?->name ?? 'Sistem' }}</p>
                                                @if($update->photos->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach($update->photos as $photo)
                                                    <a href="{{ asset('storage/' . $photo->file_path) }}" data-lightbox="order-{{$serviceOrder->id}}">
                                                        <img src="{{ asset('storage/' . $photo->file_path) }}" class="h-12 w-12 rounded object-cover border">
                                                    </a>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right w-48">
                                                @if($update->status_from && $update->status_to && $update->status_from !== $update->status_to)
                                                    <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-600">Status &rarr; {{ $update->status_to }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td class="px-4 py-4 text-sm text-center text-gray-500">Belum ada update.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Kartu Status & Aksi Cepat --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        {{-- ... (kode badge status berwarna dari sebelumnya) ... --}}
                        @php
                            $statusColor = 'bg-gray-100 text-gray-800'; // Default
                            switch ($serviceOrder->status) {
                                case 'Pending': case 'Menunggu Diagnosa': $statusColor = 'bg-yellow-100 text-yellow-800'; break;
                                case 'In Progress': case 'Diagnosing': case 'Menunggu Sparepart': case 'Pengujian': case 'Persetujuan Diterima': $statusColor = 'bg-blue-100 text-blue-800'; break;
                                case 'Completed': case 'Picked Up': $statusColor = 'bg-green-100 text-green-800'; break;
                                case 'Cancelled': case 'Quotation Ditolak': $statusColor = 'bg-red-100 text-red-800'; break;
                                case 'Menunggu Persetujuan Pelanggan': $statusColor = 'bg-orange-100 text-orange-800'; break;
                            }
                        @endphp
                        <p class="w-full text-center px-4 py-2 text-lg font-bold rounded-full {{ $statusColor }}">{{ $serviceOrder->status }}</p>

                        @if ($serviceOrder->customer && $serviceOrder->customer->phone_number)
                                @php
                                    $phoneNumber = preg_replace('/^0/', '62', $serviceOrder->customer->phone_number);
                                    $message = "Halo " . $serviceOrder->customer->name . ", ini update servis Anda #" . $serviceOrder->service_order_number . "...";
                                    $waLink = "https://wa.me/" . $phoneNumber . "?text=" . urlencode($message);
                                @endphp
                                <a href="{{ $waLink }}" target="_blank" class="mt-2 inline-flex items-center text-xs text-green-600 hover:text-green-800 font-semibold">
                                    {{-- <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.358 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                    </svg> --}}
                                    Hubungi via WhatsApp
                                </a>
                            @endif
                    </div>

                    {{-- Kartu Tambah Update --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-3">Tambah Update Progres</h3>
                        {{-- Form untuk menambah update (tidak berubah) --}}
                        <form action="{{ route('admin.service-orders.updates.store', $serviceOrder->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="update_notes" class="block text-sm font-medium text-gray-700">Catatan Update</label>
                                <textarea name="notes" id="update_notes" rows="4" class="mt-1 block w-full text-sm border-gray-300 rounded-md" required>{{ old('notes') }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label for="new_status" class="block text-sm font-medium text-gray-700">Ubah Status Ke</label>
                                <select name="new_status" id="new_status" class="mt-1 block w-full text-sm border-gray-300 rounded-md">
                                    <option value="">-- Jangan Ubah Status --</option>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="photos" class="block text-sm font-medium text-gray-700">Unggah Foto Bukti</label>
                                <input type="file" name="photos[]" id="photos" class="mt-1 block w-full text-sm" multiple>
                            </div>
                            <input type="hidden" name="update_type" value="Update Admin">
                            <div>
                                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg">Simpan Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>