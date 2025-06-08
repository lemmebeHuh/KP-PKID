<x-app-layout> {{-- Menggunakan layout Breeze yang sama --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Riwayat Order Servis Anda</h3>

                    @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($serviceOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.
                                        Servis</th>
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
                                @foreach ($serviceOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{
                                        $order->service_order_number }}</td>
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
                                        $order->technician->name : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        {{-- Link ke halaman detail servis pelanggan (akan kita buat rutenya) --}}
                                        <a href="{{ route('pelanggan.service-orders.show', $order->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            Lihat Detail
                                        </a>
                                        {{-- Atau kita bisa buat halaman detail khusus untuk pelanggan yang login --}}
                                        {{-- <a href="{{ route('pelanggan.service-orders.show', $order->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a> --}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $serviceOrders->links() }}
                    </div>
                    @else
                    <p>Anda belum memiliki riwayat order servis.</p>
                    {{-- Mungkin tambahkan link untuk membuat order baru atau menghubungi Pangkalan Komputer ID --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>