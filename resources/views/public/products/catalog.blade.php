@extends('layouts.public')

@section('title', 'Katalog Produk - Pangkalan Komputer ID')

@section('content')
<div class="container mx-auto mt-6 p-4">
    <header class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Katalog Produk Kami</h1>
        <p class="text-gray-600">Temukan berbagai produk kebutuhan komputer Anda.</p>
    </header>

    @if($products && $products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out flex flex-col">
                <a href="{{ route('products.show-public', $product->slug) }}">
                    @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover rounded-t-lg">
                    @else
                        <img src="https://via.placeholder.com/400x250.png?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover rounded-t-lg">
                    @endif
                </a>
                <div class="p-4 flex-grow flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1" style="min-height: 3.5rem;">
                        <a href="{{ route('products.show-public', $product->slug) }}" class="hover:text-indigo-600">{{ Str::limit($product->name, 50) }}</a>
                    </h3>
                    @if($product->category)
                    <p class="text-xs text-gray-500 mb-2">{{ $product->category->name }}</p>
                    @endif
                    <div class="mt-auto">
                        <p class="text-xl font-bold text-indigo-600 mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <a href="{{ route('products.show-public', $product->slug) }}" class="block w-full text-center bg-indigo-500 text-white py-2 rounded-md hover:bg-indigo-600 transition-colors duration-300 text-sm font-medium">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <p class="text-gray-500 col-span-full text-center py-10">Belum ada produk yang tersedia untuk ditampilkan saat ini.</p>
    @endif
</div>
@endsection