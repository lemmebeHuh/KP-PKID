<x-app-layout> {{-- Menggunakan layout Breeze yang sama --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Teknisi - Daftar Tugas Servis Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($assignedOrders->count() > 0)
                    <div class="overflow-x-auto">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($assignedOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{
                                        $order->service_order_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->customer ?
                                        $order->customer->name : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->device_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->date_received ?
                                        $order->date_received->format('d M Y, H:i') : '-'}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('teknisi.service-orders.show', $order->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            Lihat Detail & Update
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $assignedOrders->links() }}
                    </div>
                    @else
                    <p>Tidak ada order servis yang ditugaskan kepada Anda saat ini atau semua sudah selesai.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>