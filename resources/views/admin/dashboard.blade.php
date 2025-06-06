<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Bagian Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                {{-- Card 1: Order Servis Berjalan --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 truncate">Order Servis Berjalan</h3>
                    <p class="mt-1 text-3xl font-semibold text-indigo-600">{{ $ongoingServiceOrdersCount }}</p>
                </div>
                {{-- Card 2: Komplain Terbuka --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 truncate">Komplain Terbuka</h3>
                    <p class="mt-1 text-3xl font-semibold text-red-600">{{ $openComplaintsCount }}</p>
                </div>
                {{-- Card 3: Total Pengguna --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 truncate">Total Pengguna</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-700">{{ $totalUsersCount }}</p>
                </div>
                {{-- Card 4: Total Produk --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 truncate">Total Produk</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-700">{{ $totalProductsCount }}</p>
                </div>
            </div>

            {{-- Bagian Aktivitas Terbaru --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Kolom Kiri: Order Servis Terbaru --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Servis Terbaru</h3>
                        <div class="space-y-4">
                            @forelse($latestServiceOrders as $order)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('admin.service-orders.show', $order->id) }}" class="text-sm font-semibold text-indigo-600 hover:underline">{{ $order->service_order_number }}</a>
                                        <p class="text-sm text-gray-600">{{ $order->customer->name ?? 'N/A' }} - {{ $order->device_type }}</p>
                                    </div>
                                    <div class="text-sm text-gray-500 text-right">
                                        {{ $order->date_received->diffForHumans() }} <br>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $order->status }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Tidak ada order servis baru.</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.service-orders.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">Lihat semua order servis &rarr;</a>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Ulasan Terbaru --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ulasan Pelanggan Terbaru</h3>
                        <div class="space-y-4">
                            @forelse($latestReviews as $review)
                                <div class="border-l-4 border-yellow-400 pl-4">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-gray-800">{{ $review->customer->name ?? 'N/A' }}</p>
                                        <div class="text-sm text-yellow-500">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">&#9733;</span>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1 italic">"{{ Str::limit($review->comment, 80) ?: 'Tidak ada komentar.' }}"</p>
                                    <a href="{{ route('admin.reviews.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat ulasan</a>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada ulasan baru.</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.reviews.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">Lihat semua ulasan &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>