<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Order Servis') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                            <p class="font-bold">Sukses</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                         <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p class="font-bold">Error</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- Header Tabel: Tombol Tambah dan Filter/Search --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                        {{-- Tombol Tambah --}}
                        <a href="{{ route('admin.service-orders.create') }}" class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Tambah Order Servis
                        </a>
                        {{-- Placeholder untuk Search/Filter --}}
                        <div class="w-full sm:w-auto mt-4 sm:mt-0">
    <form action="{{ route('admin.service-orders.index') }}" method="GET" class="flex">
        <input type="text" name="search" class="w-full sm:w-64 border-gray-300 rounded-l-lg shadow-sm focus:border-primary focus:ring-primary text-sm" placeholder="Cari no. servis, nama, perangkat..." value="{{ request('search') }}">
        <button type="submit" class="p-2 bg-primary text-white rounded-r-lg hover:bg-primary-dark">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
        </button>
    </form>
</div>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="overflow-x-auto border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Servis</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perangkat</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Diterima</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teknisi</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($serviceOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->service_order_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $order->customer ? $order->customer->name : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($order->device_type, 20) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->date_received ? $order->date_received->translatedFormat('d M Y') : '-'}}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $statusColor = 'bg-gray-100 text-gray-800'; // Default
                                                switch ($order->status) {
                                                    case 'Pending':
                                                    case 'Menunggu Diagnosa':
                                                        $statusColor = 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'In Progress':
                                                    case 'Diagnosing':
                                                    case 'Menunggu Sparepart':
                                                    case 'Pengujian':
                                                        $statusColor = 'bg-blue-100 text-blue-800';
                                                        break;
                                                    case 'Completed':
                                                    case 'Picked Up':
                                                    case 'Persetujuan Diterima':
                                                        $statusColor = 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'Cancelled':
                                                    case 'Quotation Ditolak':
                                                        $statusColor = 'bg-red-100 text-red-800';
                                                        break;
                                                    case 'Menunggu Persetujuan Pelanggan':
                                                        $statusColor = 'bg-orange-100 text-orange-800';
                                                        break;
                                                }
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->technician ? $order->technician->name : 'Belum Ditugaskan' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- Dropdown Aksi --}}
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button class="inline-flex items-center px-2 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                                    </button>
                                                </x-slot>
                                                <x-slot name="content">
                                                    <x-dropdown-link :href="route('admin.service-orders.show', $order->id)">Lihat Detail</x-dropdown-link>
                                                    <x-dropdown-link :href="route('admin.service-orders.edit', $order->id)">Edit / Update</x-dropdown-link>
                                                    <a href="{{ route('admin.service-orders.print-receipt', $order->id) }}" target="_blank" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out">
                                                        Cetak Karcis
                                                    </a>

                                                    @if ($order->customer && $order->customer->phone_number)
                                                        @php
                                                            $phoneNumber = preg_replace('/^0/', '62', $order->customer->phone_number);
                                                            $trackingUrl = route('tracking.result', ['service_order_number' => $order->service_order_number]);
                                                            $messageText  = "Halo *" . $order->customer->name . "*,\n\nAda update untuk servis Anda di Pangkalan Komputer ID.\n\nNo. Servis: *" . $order->service_order_number . "*\nStatus Saat Ini: *" . $order->status . "*\n\nSilakan lihat detail lengkapnya pada link di bawah ini:\n\n" . $trackingUrl . "\n\nTerima kasih.";
                                                            $waLink = "https://wa.me/" . $phoneNumber . "?text=" . urlencode($messageText);
                                                        @endphp
                                                        <a href="{{ $waLink }}" target="_blank" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                            Kirim Notifikasi WA
                                                        </a>
                                                    @endif

                                                    <div class="border-t border-gray-100"></div>

                                                    <form action="{{ route('admin.service-orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menonaktifkan (soft delete) order servis ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm leading-5 text-red-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">Nonaktifkan</button>
                                                    </form>
                                                </x-slot>
                                            </x-dropdown>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada order servis.</td>
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