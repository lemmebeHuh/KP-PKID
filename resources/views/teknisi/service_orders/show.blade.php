<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Tugas Servis: ') }} {{ $serviceOrder->service_order_number }}
            </h2>
            <a href="{{ route('teknisi.dashboard') }}" class="text-sm text-primary hover:text-indigo-900">
                &larr; Kembali ke Daftar Tugas
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Kolom Informasi Utama Order (mirip admin, bisa disesuaikan) --}}
            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4">Informasi Order</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div><strong>No. Servis:</strong> {{ $serviceOrder->service_order_number }}</div>
                        <div><strong>Status Saat Ini:</strong> <span
                                class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{
                                $serviceOrder->status }}</span></div>
                        <div><strong>Pelanggan:</strong> {{ $serviceOrder->customer ? $serviceOrder->customer->name :
                            'N/A' }}</div>
                        <div><strong>No. HP Pelanggan:</strong> {{ $serviceOrder->customer &&
                            $serviceOrder->customer->phone_number ? $serviceOrder->customer->phone_number : '-' }}</div>
                        <div><strong>Tgl Diterima:</strong> {{ $serviceOrder->date_received ?
                            $serviceOrder->date_received->format('d M Y, H:i') : '-' }}</div>
                        <div><strong>Estimasi Selesai (dari Admin):</strong> {{ $serviceOrder->estimated_completion_date
                            ? \Carbon\Carbon::parse($serviceOrder->estimated_completion_date)->format('d M Y') : '-' }}
                        </div>
                    </div>

                    <h4 class="font-medium mt-3">Detail Perangkat:</h4>
                    <p><strong>Jenis:</strong> {{ $serviceOrder->device_type }}</p>
                    <p><strong>Merk/Model:</strong> {{ $serviceOrder->device_brand_model ?: '-' }}</p>
                    <p><strong>No. Seri:</strong> {{ $serviceOrder->serial_number ?: '-' }}</p>
                    <p><strong>Kelengkapan:</strong> {{ $serviceOrder->accessories_received ?: '-' }}</p>

                    <h4 class="font-medium mt-3">Keluhan Awal:</h4>
                    <p class="whitespace-pre-wrap">{{ $serviceOrder->problem_description }}</p>

                    <h4 class="font-medium mt-3">Detail Diagnosa & Quotation (dari Admin):</h4>
                    <p class="whitespace-pre-wrap">{{ $serviceOrder->quotation_details ?: '-' }}</p>
                    <p><strong>Status Persetujuan Pelanggan:</strong> {{ $serviceOrder->customer_approval_status ?: '-'
                        }}</p>
                </div>
            </div>

            {{-- Kolom Riwayat Update & Tambah Update oleh Teknisi --}}
            <div class="md:col-span-1 space-y-6">
                {{-- Form Tambah Update Baru oleh Teknisi --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Tambah Update Progres (Oleh Anda)</h3>

                        @if(session('update_error')) {{-- Error bag khusus untuk form ini --}}
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('update_error') }}
                        </div>
                        @endif
                        @if ($errors->store_technician_update && $errors->store_technician_update->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Oops! Ada yang salah:</strong>
                            <ul>@foreach ($errors->store_technician_update->all() as $error)<li>{{ $error }}</li>
                                @endforeach</ul>
                        </div>
                        @endif

                        {{-- Rute ini akan kita buat methodnya nanti: teknisi.service-orders.updates.store --}}
                        <form action="{{ route('teknisi.service-orders.updates.store', $serviceOrder->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- Field Catatan Update (sudah ada) --}}
                            <div class="mb-4">
                                <label for="update_notes" class="block text-sm font-medium text-gray-700">Catatan
                                    Pengerjaan/Hasil Diagnosa</label>
                                <textarea name="notes" id="update_notes" rows="4" class="mt-1 block w-full"
                                    required>{{ old('notes') }}</textarea>
                            </div>

                            {{-- !! TAMBAHKAN FIELD INI !! --}}
                            <div class="mb-4">
                                <label for="quotation_details" class="block text-sm font-medium text-gray-700">Update
                                    Detail Diagnosa & Quotation (jika ada)</label>
                                <textarea name="quotation_details" id="quotation_details" rows="4"
                                    class="mt-1 block w-full"
                                    placeholder="Catat hasil diagnosa, pekerjaan yang diusulkan, dan rincian biaya di sini...">{{ old('quotation_details', $serviceOrder->quotation_details) }}</textarea>
                            </div>

                            {{-- !! TAMBAHKAN FIELD INI !! --}}
                            <div class="mb-4">
                                <label for="estimated_completion_date"
                                    class="block text-sm font-medium text-gray-700">Update Estimasi Tgl Selesai (jika
                                    ada)</label>
                                <input type="date" name="estimated_completion_date" id="estimated_completion_date"
                                    value="{{ old('estimated_completion_date', $serviceOrder->estimated_completion_date ? \Carbon\Carbon::parse($serviceOrder->estimated_completion_date)->format('Y-m-d') : '') }}"
                                    class="mt-1 block w-full">
                            </div>

                            {{-- Field Ubah Status (sudah ada) --}}
                            <div class="mb-4">
                                <label for="new_status" class="block text-sm font-medium text-gray-700">Ubah Status
                                    Order Ke (Opsional)</label>
                                <select name="new_status" id="new_status" class="mt-1 block w-full">
                                    <option value="">-- Jangan Ubah Status --</option>
                                    @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('new_status')==$value ? 'selected' : '' }}>{{
                                        $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Field Unggah Foto (sudah ada) --}}
                            <div class="mb-4">
                                <label for="photos" ...></label>
                                <input type="file" name="photos[]" ...>
                            </div>
                            <input type="hidden" name="update_type" value="Update Teknisi">

                            <div>
                                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg">
                                    Simpan Update Progres
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Riwayat Update (sama seperti di admin show page) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Riwayat Update & Progres</h3>
                        @if($serviceOrder->updates && $serviceOrder->updates->count() > 0)
                        <div class="space-y-4">
                            @foreach($serviceOrder->updates as $update)
                            <div class="border rounded-md p-3 text-sm">
                                <p class="font-semibold">{{ $update->update_type ?: 'Update' }} - <span
                                        class="font-normal text-gray-600">{{ $update->created_at->format('d M Y, H:i')
                                        }}</span></p>
                                @if($update->updatedBy)
                                <p class="text-xs text-gray-500">Oleh: {{ $update->updatedBy->name }}</p>
                                @endif
                                @if($update->status_from || $update->status_to)
                                <p class="text-xs text-blue-600">Status: {{ $update->status_from ?: 'N/A' }} &rarr; {{
                                    $update->status_to ?: 'N/A' }}</p>
                                @endif
                                <p class="mt-1 whitespace-pre-wrap">{{ $update->notes }}</p>
                                @if($update->photos && $update->photos->count() > 0)
                                <div class="mt-2 grid grid-cols-3 gap-2">
                                    @foreach($update->photos as $photo)
                                    <a href="{{ asset('storage/' . $photo->file_path) }}"
                                        data-lightbox="order-{{$serviceOrder->id}}-update-{{$update->id}}"
                                        data-title="{{ $photo->caption ?: $update->notes }}">
                                        <img src="{{ asset('storage/' . $photo->file_path) }}"
                                            alt="{{ $photo->caption ?: 'Bukti Foto' }}"
                                            class="h-16 w-full object-cover rounded">
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Belum ada update untuk order servis ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>