<x-app-layout>
    <x-slot name="title">{{ 'Detail Order ' . $serviceOrder->service_order_number }}</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Order Servis') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola dan lihat progres untuk nomor order: 
                    <span class="font-bold text-primary">{{ $serviceOrder->service_order_number }}</span>
                </p>
            </div>
            <div class="mt-2 sm:mt-0 flex space-x-2">
                <a href="{{ route('admin.service-orders.index') }}" class="text-sm font-medium text-gray-600 hover:text-primary">
                    &larr; Kembali ke Daftar Order
                </a>
                <a href="{{ route('admin.service-orders.edit', $serviceOrder->id) }}" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg text-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Order
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses/Error --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Grid Utama --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- Kolom Kiri: Detail & Aksi --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Kartu Status & Info Kunci --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                         @php
                            $statusInfo = ['color' => 'bg-gray-100 text-gray-800', 'description' => 'Status tidak dikenali.'];
                            switch ($serviceOrder->status) {
                                case 'Pending': case 'Menunggu Diagnosa': $statusInfo = ['color' => 'bg-yellow-100 text-yellow-800', 'description' => 'Order baru, menunggu diagnosa teknisi.']; break;
                                case 'In Progress': case 'Diagnosing': case 'Menunggu Sparepart': case 'Pengujian': case 'Persetujuan Diterima': $statusInfo = ['color' => 'bg-blue-100 text-blue-800', 'description' => 'Order sedang dalam proses pengerjaan.']; break;
                                case 'Completed': case 'Picked Up': $statusInfo = ['color' => 'bg-green-100 text-green-800', 'description' => 'Servis telah selesai.']; break;
                                case 'Cancelled': case 'Quotation Ditolak': $statusInfo = ['color' => 'bg-red-100 text-red-800', 'description' => 'Proses servis dibatalkan.']; break;
                                case 'Menunggu Persetujuan Pelanggan': $statusInfo = ['color' => 'bg-orange-100 text-orange-800', 'description' => 'Menunggu konfirmasi dari pelanggan mengenai penawaran.']; break;
                            }
                        @endphp
                        <h3 class="text-base font-semibold text-gray-500">Status Terkini</h3>
                        <p class="mt-1 px-4 py-2 text-lg font-bold rounded-full text-center {{ $statusInfo['color'] }}">
                            {{ $serviceOrder->status }}
                        </p>
                        <p class="mt-3 text-xs text-gray-600 italic text-center">{{ $statusInfo['description'] }}</p>
                        <div class="mt-4 pt-4 border-t border-gray-200 text-sm space-y-2">
                            <p><strong>Teknisi:</strong> {{ $serviceOrder->technician->name ?? 'Belum Ditugaskan' }}</p>
                            <p><strong>Biaya Final:</strong> {{ $serviceOrder->final_cost ? 'Rp ' . number_format($serviceOrder->final_cost, 0, ',', '.') : '-' }}</p>
                        </div>
                    </div>

                    {{-- Kartu Pelanggan --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-3">Informasi Pelanggan</h3>
                        <div class="space-y-2 text-sm">
                            <p><strong>Nama:</strong> {{ $serviceOrder->customer->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $serviceOrder->customer->email ?? 'N/A' }}</p>
                            <p><strong>No. HP:</strong> {{ $serviceOrder->customer->phone_number ?? '-' }}</p>
                            @if ($serviceOrder->customer && $serviceOrder->customer->phone_number)
                                @php
                                    $phoneNumber = preg_replace('/^0/', '62', $serviceOrder->customer->phone_number);
                                    $message = "Halo " . $serviceOrder->customer->name . ", ini update servis Anda #" . $serviceOrder->service_order_number . "...";
                                    $waLink = "https://wa.me/" . $phoneNumber . "?text=" . urlencode($message);
                                @endphp
                                <a href="{{ $waLink }}" target="_blank" class="mt-2 inline-flex items-center text-xs text-green-600 hover:text-green-800 font-semibold">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 24 24">...</svg>
                                    Hubungi via WhatsApp
                                </a>
                            @endif
                        </div>
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

                {{-- Kolom Kanan: Timeline Riwayat --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-6 border-b pb-3">Riwayat & Detail Pengerjaan</h3>
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>