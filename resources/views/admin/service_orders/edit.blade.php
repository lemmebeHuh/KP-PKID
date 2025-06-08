<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Order Servis: ') }} {{ $serviceOrder->service_order_number }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong class="font-bold">Oops! Ada yang salah:</strong>
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.service-orders.update', $serviceOrder->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-semibold mb-2">Nomor Servis: {{ $serviceOrder->service_order_number }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">Tanggal Diterima: {{
                            $serviceOrder->date_received->format('d M Y, H:i') }}</p>

                        <h3 class="text-lg font-semibold mb-2 mt-6">Informasi Pelanggan</h3>
                        <div class="mb-4">
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">Pelanggan</label>
                            <select name="customer_id" id="customer_id" class="mt-1 block w-full bg-gray-100" disabled>
                                {{-- Biasanya customer tidak diubah --}}
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $serviceOrder->customer_id) ==
                                    $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="customer_id" value="{{ $serviceOrder->customer_id }}"> {{-- Kirim
                            ID customer secara tersembunyi --}}
                        </div>

                        <h3 class="text-lg font-semibold mb-2 mt-6">Informasi Perangkat & Keluhan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="device_type" class="block text-sm font-medium text-gray-700">Jenis
                                    Perangkat</label>
                                <input type="text" name="device_type" id="device_type"
                                    value="{{ old('device_type', $serviceOrder->device_type) }}"
                                    class="mt-1 block w-full" required>
                            </div>
                            <div>
                                <label for="device_brand_model" class="block text-sm font-medium text-gray-700">Merk &
                                    Model</label>
                                <input type="text" name="device_brand_model" id="device_brand_model"
                                    value="{{ old('device_brand_model', $serviceOrder->device_brand_model) }}"
                                    class="mt-1 block w-full">
                            </div>
                            <div>
                                <label for="serial_number" class="block text-sm font-medium text-gray-700">Nomor
                                    Seri</label>
                                <input type="text" name="serial_number" id="serial_number"
                                    value="{{ old('serial_number', $serviceOrder->serial_number) }}"
                                    class="mt-1 block w-full">
                            </div>
                            <div>
                                <label for="accessories_received"
                                    class="block text-sm font-medium text-gray-700">Kelengkapan Diterima</label>
                                <textarea name="accessories_received" id="accessories_received" rows="2"
                                    class="mt-1 block w-full">{{ old('accessories_received', $serviceOrder->accessories_received) }}</textarea>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="problem_description" class="block text-sm font-medium text-gray-700">Deskripsi
                                Keluhan Pelanggan</label>
                            <textarea name="problem_description" id="problem_description" rows="3"
                                class="mt-1 block w-full"
                                required>{{ old('problem_description', $serviceOrder->problem_description) }}</textarea>
                        </div>

                        <h3 class="text-lg font-semibold mb-2 mt-6">Detail & Status Servis</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="assigned_technician_id"
                                    class="block text-sm font-medium text-gray-700">Tugaskan ke Teknisi</label>
                                <select name="assigned_technician_id" id="assigned_technician_id"
                                    class="mt-1 block w-full">
                                    <option value="">-- Belum Ditugaskan --</option>
                                    @foreach ($technicians as $technician)
                                    <option value="{{ $technician->id }}" {{ old('assigned_technician_id',
                                        $serviceOrder->assigned_technician_id) == $technician->id ? 'selected' : '' }}>
                                        {{ $technician->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status
                                    Servis</label>
                                <select name="status" id="status" class="mt-1 block w-full" required>
                                    @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $serviceOrder->status) == $value ?
                                        'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="estimated_completion_date"
                                    class="block text-sm font-medium text-gray-700">Estimasi Tgl Selesai</label>
                                <input type="date" name="estimated_completion_date" id="estimated_completion_date"
                                    value="{{ old('estimated_completion_date', $serviceOrder->estimated_completion_date ? \Carbon\Carbon::parse($serviceOrder->estimated_completion_date)->format('Y-m-d') : '') }}"
                                    class="mt-1 block w-full">
                            </div>
                            <div>
                                <label for="final_cost" class="block text-sm font-medium text-gray-700">Biaya Final
                                    (Rp)</label>
                                <input type="number" name="final_cost" id="final_cost"
                                    value="{{ old('final_cost', $serviceOrder->final_cost) }}" class="mt-1 block w-full"
                                    min="0" placeholder="Isi jika sudah ada">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="quotation_details" class="block text-sm font-medium text-gray-700">Detail
                                Diagnosa & Quotation Biaya</label>
                            <textarea name="quotation_details" id="quotation_details" rows="4" class="mt-1 block w-full"
                                placeholder="Catat hasil diagnosa, pekerjaan yang diusulkan, dan rincian biaya di sini...">{{ old('quotation_details', $serviceOrder->quotation_details) }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="customer_approval_status" class="block text-sm font-medium text-gray-700">Status
                                Persetujuan Pelanggan</label>
                            <select name="customer_approval_status" id="customer_approval_status"
                                class="mt-1 block w-full">
                                <option value="">-- Pilih Status Persetujuan --</option>
                                @foreach ($customerApprovalStatuses as $value => $label)
                                <option value="{{ $value }}" {{ old('customer_approval_status', $serviceOrder->
                                    customer_approval_status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kolom tanggal selesai & tanggal diambil akan diisi saat statusnya berubah ke sana --}}
                        <h3 class="text-lg font-semibold mb-2 mt-6">Informasi Garansi (Opsional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="warranty_start_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Mulai Garansi</label>
                                <input type="date" name="warranty_start_date" id="warranty_start_date"
                                    value="{{ old('warranty_start_date', $serviceOrder->warranty ? \Carbon\Carbon::parse($serviceOrder->warranty->start_date)->format('Y-m-d') : '') }}"
                                    class="mt-1 block w-full">
                            </div>
                            <div>
                                <label for="warranty_end_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Berakhir Garansi</label>
                                <input type="date" name="warranty_end_date" id="warranty_end_date"
                                    value="{{ old('warranty_end_date', $serviceOrder->warranty ? \Carbon\Carbon::parse($serviceOrder->warranty->end_date)->format('Y-m-d') : '') }}"
                                    class="mt-1 block w-full">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="warranty_terms" class="block text-sm font-medium text-gray-700">Syarat &
                                Ketentuan Garansi (Opsional)</label>
                            <textarea name="warranty_terms" id="warranty_terms" rows="3" class="mt-1 block w-full"
                                placeholder="Jelaskan syarat dan ketentuan garansi...">{{ old('warranty_terms', $serviceOrder->warranty ? $serviceOrder->warranty->terms : '') }}</textarea>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Update
                                Order Servis</button>
                            <a href="{{ route('admin.service-orders.index') }}" class="ml-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>