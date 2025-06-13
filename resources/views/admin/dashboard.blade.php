<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Bagian Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 truncate">Order Servis Berjalan</h3>
                    <p class="mt-1 text-3xl font-semibold text-primary">{{ $ongoingServiceOrdersCount }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 truncate">Komplain Terbuka</h3>
                    <p class="mt-1 text-3xl font-semibold text-red-600">{{ $openComplaintsCount }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 truncate">Total Pengguna</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-700">{{ $totalUsersCount }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 truncate">Total Produk</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-700">{{ $totalProductsCount }}</p>
                </div>
            </div>

            {{-- Baris untuk Chart dan Pelanggan Top --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Order Servis (6 Bulan Terakhir)</h3>
                        <div class="h-64"> {{-- Beri tinggi pada container canvas --}}
                            <canvas id="serviceOrderChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                         <h3 class="text-lg font-medium text-gray-900 mb-4">Top 3 Pelanggan Setia</h3>
                         <div class="space-y-4">
                            @forelse($topCustomers as $customer)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-200 rounded-full flex items-center justify-center">
                                        <span class="text-primary font-bold">{{ substr($customer->name, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $customer->service_orders_count }} kali servis</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Data belum cukup untuk menampilkan pelanggan top.</p>
                            @endforelse
                         </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Aktivitas Terbaru --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Servis Terbaru</h3>
                        <div class="space-y-4">
                            @forelse($latestServiceOrders as $order)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('admin.service-orders.show', $order->id) }}" class="text-sm font-semibold text-primary hover:underline">{{ $order->service_order_number }}</a>
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
                            <a href="{{ route('admin.service-orders.index') }}" class="text-sm font-medium text-primary hover:underline">Lihat semua order servis &rarr;</a>
                        </div>
                    </div>
                </div>

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
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada ulasan baru.</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.reviews.index') }}" class="text-sm font-medium text-primary hover:underline">Lihat semua ulasan &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts') {{-- Kirim script ini ke stack 'scripts' di layout jika Anda menggunakannya --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('serviceOrderChart');
            if (ctx) { // Hanya jalankan jika elemen canvas ada
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Jumlah Order Servis',
                            data: {!! json_encode($chartData) !!},
                            backgroundColor: 'rgba(79, 70, 229, 0.5)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    // Pastikan ticks hanya integer jika jumlah order sedikit
                                    precision: 0
                                }
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>