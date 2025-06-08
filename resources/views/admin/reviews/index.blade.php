<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Ulasan Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8"> {{-- max-w-full untuk tabel lebar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Opsi filter bisa ditambahkan di sini nanti --}}

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Ulasan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Order Servis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Komentar</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Disetujui</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($reviews as $review)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $review->created_at->translatedFormat('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($review->serviceOrder)
                                                <a href="{{ route('admin.service-orders.show', $review->serviceOrder->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $review->serviceOrder->service_order_number }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $review->customer ? $review->customer->name : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">&#9733;</span>
                                            @endfor
                                            ({{ $review->rating }})
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $review->comment }}">
                                            {{ Str::limit($review->comment, 70) ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            @if($review->is_approved)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Ya</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{-- Tombol Aksi (misal: Setujui/Batal Setujui, Edit, Hapus) bisa ditambahkan nanti --}}
                                            <a href="#" class="text-gray-400 hover:text-gray-600">Kelola</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada ulasan pelanggan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>