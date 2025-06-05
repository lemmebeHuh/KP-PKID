<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Komplain Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Opsi filter komplain berdasarkan status bisa ditambahkan di sini --}}

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Komplain</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Order Servis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subjek</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($complaints as $complaint)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $complaint->created_at->translatedFormat('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $complaint->customer ? $complaint->customer->name : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($complaint->serviceOrder)
                                                <a href="{{ route('admin.service-orders.show', $complaint->serviceOrder->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $complaint->serviceOrder->service_order_number }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm max-w-sm truncate" title="{{$complaint->subject}}">{{ Str::limit($complaint->subject, 50) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($complaint->status == 'Open') bg-red-100 text-red-800 @elseif($complaint->status == 'In Progress') bg-yellow-100 text-yellow-800 @elseif($complaint->status == 'Resolved') bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                                                {{ $complaint->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.complaints.edit', $complaint->id) }}" class="text-indigo-600 hover:text-indigo-900">Kelola/Lihat Detail</a>
                                            {{-- Tombol Hapus komplain (jika perlu) --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada komplain pelanggan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $complaints->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>