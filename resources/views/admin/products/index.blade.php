<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Produk') }}
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

                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                        <a href="{{ route('admin.products.create') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Tambah Produk Baru
                        </a>
                        <div class="w-full sm:w-auto mt-4 sm:mt-0">
    <form action="{{ route('admin.products.index') }}" method="GET" class="flex">
        <input type="text" name="search" class="w-full sm:w-64 border-gray-300 rounded-l-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Cari nama produk..." value="{{ request('search') }}">
        <button type="submit" class="p-2 bg-indigo-600 text-white rounded-r-lg hover:bg-indigo-700">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
        </button>
    </form>
</div>
                    </div>

                    <div class="overflow-x-auto border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stok</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/150' }}" alt="{{ $product->name }}" class="h-10 w-10 object-cover rounded-md">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Str::limit($product->name, 40) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name ?? 'Tanpa Kategori' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $product->stock_quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger"><button class="inline-flex items-center px-2 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg></button></x-slot>
                                                <x-slot name="content">
                                                    <x-dropdown-link :href="route('admin.products.edit', $product->id)">Edit</x-dropdown-link>
                                                    <div class="border-t border-gray-100"></div>
                                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm leading-5 text-red-700 hover:bg-gray-100">Hapus</button>
                                                    </form>
                                                </x-slot>
                                            </x-dropdown>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada produk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>