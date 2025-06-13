<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Order Servis: ') }} {{ $serviceOrder->service_order_number }}
            </h2>
            <a href="{{ route('admin.service-orders.edit', $serviceOrder->id) }}"
                class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">
                Edit Order Ini
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Kolom Informasi Utama Order --}}
            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4">Informasi Utama</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div><strong>No. Servis:</strong> {{ $serviceOrder->service_order_number }}</div>
                        <div><strong>Status Saat Ini:</strong> <span
                                class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{
                                $serviceOrder->status }}</span></div>
                        <div><strong>Pelanggan:</strong> {{ $serviceOrder->customer ? $serviceOrder->customer->name :
                            'N/A' }}</div>
                        <div><strong>Email Pelanggan:</strong> {{ $serviceOrder->customer ?
                            $serviceOrder->customer->email : 'N/A' }}</div>
                        <div><strong>No. HP Pelanggan:</strong> {{ $serviceOrder->customer &&
                            $serviceOrder->customer->phone_number ? $serviceOrder->customer->phone_number : '-' }}</div>
                        <div><strong>Teknisi Ditugaskan:</strong> {{ $serviceOrder->technician ?
                            $serviceOrder->technician->name : 'Belum Ditugaskan' }}</div>
                        <div><strong>Tgl Diterima:</strong> {{ $serviceOrder->date_received ?
                            $serviceOrder->date_received->format('d M Y, H:i') : '-' }}</div>
                        <div><strong>Estimasi Selesai:</strong> {{ $serviceOrder->estimated_completion_date ?
                            \Carbon\Carbon::parse($serviceOrder->estimated_completion_date)->format('d M Y') : '-' }}
                        </div>
                        <div><strong>Biaya Final:</strong> {{ $serviceOrder->final_cost ? 'Rp ' .
                            number_format($serviceOrder->final_cost, 0, ',', '.') : '-' }}</div>
                        <div><strong>Tgl Selesai Aktual:</strong> {{ $serviceOrder->date_completed ?
                            $serviceOrder->date_completed->format('d M Y, H:i') : '-' }}</div>
                        <div><strong>Tgl Diambil:</strong> {{ $serviceOrder->date_picked_up ?
                            $serviceOrder->date_picked_up->format('d M Y, H:i') : '-' }}</div>
                    </div>

                    <h4 class="font-medium mt-3">Detail Perangkat:</h4>
                    <p><strong>Jenis:</strong> {{ $serviceOrder->device_type }}</p>
                    <p><strong>Merk/Model:</strong> {{ $serviceOrder->device_brand_model ?: '-' }}</p>
                    <p><strong>No. Seri:</strong> {{ $serviceOrder->serial_number ?: '-' }}</p>
                    <p><strong>Kelengkapan:</strong> {{ $serviceOrder->accessories_received ?: '-' }}</p>

                    <h4 class="font-medium mt-3">Keluhan Awal:</h4>
                    <p class="whitespace-pre-wrap">{{ $serviceOrder->problem_description }}</p>

                    <h4 class="font-medium mt-3">Detail Diagnosa & Quotation:</h4>
                    <p class="whitespace-pre-wrap">{{ $serviceOrder->quotation_details ?: '-' }}</p>
                    <p><strong>Status Persetujuan Pelanggan:</strong> {{ $serviceOrder->customer_approval_status ?: '-'
                        }}</p>

                    @if($serviceOrder->warranty)
                    <h4 class="font-medium mt-3">Informasi Garansi:</h4>
                    <p><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($serviceOrder->warranty->start_date)->format('d
                        M Y') }}</p>
                    <p><strong>Berakhir:</strong> {{ \Carbon\Carbon::parse($serviceOrder->warranty->end_date)->format('d
                        M Y') }}</p>
                    <p><strong>Syarat:</strong> {{ $serviceOrder->warranty->terms ?: '-' }}</p>
                    @endif
                </div>
            </div>

            {{-- Kolom Riwayat Update & Tambah Update --}}
            <div class="md:col-span-1 space-y-6">
                {{-- Form Tambah Update Baru --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Tambah Update Progres/Catatan</h3>

                        @if ($errors->store_update && $errors->store_update->any()) {{-- Menampilkan error khusus untuk
                        form ini --}}
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Oops! Ada yang salah saat menambah update:</strong>
                            <ul>@foreach ($errors->store_update->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                        @endif

                        {{-- Kita akan buat route bernama 'admin.service-orders.updates.store' nanti --}}
                        <form action="{{ route('admin.service-orders.updates.store', $serviceOrder->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="update_notes" class="block text-sm font-medium text-gray-700">Catatan
                                    Update</label>
                                <textarea name="notes" id="update_notes" rows="4" class="mt-1 block w-full"
                                    required>{{ old('notes') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="new_status" class="block text-sm font-medium text-gray-700">Ubah Status
                                    Order Ke (Opsional)</label>
                                <select name="new_status" id="new_status" class="mt-1 block w-full">
                                    <option value="">-- Jangan Ubah Status --</option>
                                    @foreach ($statuses as $value => $label) {{-- $statuses dikirim dari controller
                                    show() --}}
                                    <option value="{{ $value }}" {{ old('new_status')==$value ? 'selected' : '' }}>{{
                                        $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="photos" class="block text-sm font-medium text-gray-700">Unggah Foto Bukti
                                    (Opsional, bisa multiple)</label>
                                <input type="file" name="photos[]" id="photos" class="mt-1 block w-full" multiple>
                                <p class="text-xs text-gray-500 mt-1">Anda bisa memilih lebih dari satu file gambar.</p>
                            </div>

                            {{-- update_type bisa kita set otomatis di controller atau tambahkan input jika perlu --}}
                            <input type="hidden" name="update_type" value="Catatan Admin">


                            <div>
                                <button type="submit"
                                    class="bg-sky-500 hover:bg-gray-500 text-black font-bold py-2 px-4 rounded">
                                    Simpan Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Riwayat Update --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Riwayat Update & Progres</h3>
                        @if($serviceOrder->updates && $serviceOrder->updates->count() > 0)
                        <div class="space-y-4">
                            @foreach($serviceOrder->updates as $update)
                            <div class="border rounded-md p-3">
                                <p class="text-sm font-semibold">{{ $update->update_type ?: 'Update' }} - <span
                                        class="font-normal text-gray-600">{{ $update->created_at->format('d M Y, H:i')
                                        }}</span></p>
                                @if($update->updatedBy)
                                <p class="text-xs text-gray-500">Oleh: {{ $update->updatedBy->name }}</p>
                                @endif
                                @if($update->status_from || $update->status_to)
                                <p class="text-xs text-blue-600">Status: {{ $update->status_from ?: 'N/A' }} &rarr; {{
                                    $update->status_to ?: 'N/A' }}</p>
                                @endif
                                <p class="mt-1 text-sm whitespace-pre-wrap">{{ $update->notes }}</p>
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
</x-app-layout>