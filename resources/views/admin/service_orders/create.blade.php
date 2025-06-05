<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Order Servis Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Oops! Ada yang salah:</strong>
                            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.service-orders.store') }}" method="POST">
                        @csrf
                        {{-- Nomor Servis akan di-generate otomatis di controller --}}

                        <h3 class="text-lg font-semibold mb-2">Informasi Pelanggan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700">Pilih Pelanggan (Jika Sudah Terdaftar)</label>
                                <select name="customer_id" id="customer_id" class="mt-1 block w-full">
                                    <option value="">-- Pilih Pelanggan --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Jika pelanggan baru, kosongkan ini dan isi form di bawah.</p>
                            </div>
                        </div>
                        {{-- Opsi untuk input pelanggan baru jika tidak ada di daftar --}}
                        <div class="border-t border-gray-200 pt-4 mt-4 mb-4" id="new_customer_fields" style="{{ old('customer_id') ? 'display:none;' : '' }}">
                            <p class="text-sm font-medium text-gray-700 mb-2">Atau Input Pelanggan Baru:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="new_customer_name" class="block text-sm font-medium">Nama Pelanggan Baru</label>
                                    <input type="text" name="new_customer_name" id="new_customer_name" value="{{ old('new_customer_name') }}" class="mt-1 block w-full">
                                </div>
                                <div>
                                    <label for="new_customer_email" class="block text-sm font-medium">Email Pelanggan Baru</label>
                                    <input type="email" name="new_customer_email" id="new_customer_email" value="{{ old('new_customer_email') }}" class="mt-1 block w-full">
                                </div>
                                <div>
                                    <label for="new_customer_phone" class="block text-sm font-medium">No. HP Pelanggan Baru</label>
                                    <input type="text" name="new_customer_phone" id="new_customer_phone" value="{{ old('new_customer_phone') }}" class="mt-1 block w-full">
                                </div>
                                {{-- Password untuk pelanggan baru bisa di-generate otomatis atau dikirim via email --}}
                            </div>
                        </div>


                        <h3 class="text-lg font-semibold mb-2 mt-6">Informasi Perangkat & Keluhan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="device_type" class="block text-sm font-medium text-gray-700">Jenis Perangkat</label>
                                <input type="text" name="device_type" id="device_type" value="{{ old('device_type') }}" class="mt-1 block w-full" required placeholder="Contoh: Laptop, PC, Printer">
                            </div>
                            <div>
                                <label for="device_brand_model" class="block text-sm font-medium text-gray-700">Merk & Model (Opsional)</label>
                                <input type="text" name="device_brand_model" id="device_brand_model" value="{{ old('device_brand_model') }}" class="mt-1 block w-full" placeholder="Contoh: Acer Aspire 5">
                            </div>
                            <div>
                                <label for="serial_number" class="block text-sm font-medium text-gray-700">Nomor Seri (Opsional)</label>
                                <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" class="mt-1 block w-full">
                            </div>
                             <div>
                                <label for="accessories_received" class="block text-sm font-medium text-gray-700">Kelengkapan Diterima (Opsional)</label>
                                <textarea name="accessories_received" id="accessories_received" rows="2" class="mt-1 block w-full" placeholder="Contoh: Tas, Charger, Mouse">{{ old('accessories_received') }}</textarea>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="problem_description" class="block text-sm font-medium text-gray-700">Deskripsi Keluhan Pelanggan</label>
                            <textarea name="problem_description" id="problem_description" rows="3" class="mt-1 block w-full" required>{{ old('problem_description') }}</textarea>
                        </div>


                        <h3 class="text-lg font-semibold mb-2 mt-6">Detail Servis Awal</h3>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="assigned_technician_id" class="block text-sm font-medium text-gray-700">Tugaskan ke Teknisi (Opsional)</label>
                                <select name="assigned_technician_id" id="assigned_technician_id" class="mt-1 block w-full">
                                    <option value="">-- Pilih Teknisi --</option>
                                    @foreach ($technicians as $technician)
                                        <option value="{{ $technician->id }}" {{ old('assigned_technician_id') == $technician->id ? 'selected' : '' }}>
                                            {{ $technician->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status Awal</label>
                                <input type="text" name="status" id="status" value="{{ old('status', $initialStatus) }}" class="mt-1 block w-full bg-gray-100" readonly>
                                {{-- Atau bisa berupa select jika ada beberapa status awal yang valid --}}
                                {{-- <select name="status" id="status" class="mt-1 block w-full">
                                    <option value="Pending" {{ old('status', $initialStatus) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Menunggu Diagnosa" {{ old('status', $initialStatus) == 'Menunggu Diagnosa' ? 'selected' : '' }}>Menunggu Diagnosa</option>
                                </select> --}}
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan Order Servis</button>
                            <a href="{{ route('admin.service-orders.index') }}" class="ml-4">Batal</a>
                        </div>
                    </form>
                    <script>
                        // Sembunyikan/tampilkan field pelanggan baru berdasarkan pilihan dropdown
                        document.getElementById('customer_id').addEventListener('change', function() {
                            document.getElementById('new_customer_fields').style.display = this.value ? 'none' : 'block';
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>