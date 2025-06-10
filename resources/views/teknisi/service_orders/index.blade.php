<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Order Servis') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8"> {{-- max-w-full untuk tabel yang lebar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="mb-4">
                        <a href="{{ route('admin.service-orders.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Order Servis Baru
                        </a>
                    </div>

                    
                    <div class="overflow-x-auto"> {{-- Untuk tabel responsif jika terlalu lebar --}}
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.
                                        Servis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Perangkat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl
                                        Diterima</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teknisi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($serviceOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{
                                        $order->service_order_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->customer ?
                                        $order->customer->name : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->device_type }} {{
                                        $order->device_brand_model ? '('.$order->device_brand_model.')' : '' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->date_received ?
                                        $order->date_received->format('d M Y, H:i') : '-'}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{-- Anda bisa tambahkan logika warna berdasarkan status di sini --}}
                                                bg-blue-100 text-blue-800">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->technician ?
                                        $order->technician->name : 'Belum Ditugaskan' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.service-orders.show', $order->id) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-2">Lihat</a>
                                        <a href="{{ route('admin.service-orders.edit', $order->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Edit/Update</a>
                                        {{-- Tombol Hapus bisa ditambahkan nanti, hati-hati dengan foreign key --}}
                                        <form action="{{ route('admin.service-orders.destroy', $order->id) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menonaktifkan (soft delete) order servis ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 ml-4">Nonaktifkan</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada order
                                        servis.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $serviceOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>