<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manajemen Ulasan Pelanggan') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- ... (Notifikasi Sukses/Error) ... --}}

                    {{-- Filter Berdasarkan Rating Bintang --}}
                    <div class="mb-4">
                        <form action="{{ route('admin.reviews.index') }}" method="GET">
                            <label for="rating" class="text-sm font-medium text-gray-700">Filter berdasarkan rating:</label>
                            <select name="rating" id="rating" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Rating</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} Bintang
                                    </option>
                                @endfor
                            </select>
                        </form>
                    </div>

                    <div class="overflow-x-auto border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- ... (thead yang sudah ada di contoh sebelumnya) ... --}}
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Ulasan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Order</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Komentar</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($reviews as $review)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $review->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($review->serviceOrder)<a href="{{ route('admin.service-orders.show', $review->serviceOrder->id) }}" class="text-indigo-600">{{ $review->serviceOrder->service_order_number }}</a>@else N/A @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $review->customer->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-400">
                                        <div class="flex items-center justify-center">
                                            @for($i = 1; $i <= $review->rating; $i++)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $review->comment }}">{{ Str::limit($review->comment, 70) ?: '-' }}</td>
                                    
                                </tr>
                                @empty
                                <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada ulasan ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">{{ $reviews->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>