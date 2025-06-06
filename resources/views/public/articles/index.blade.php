@extends('layouts.public')

@section('title', 'Artikel & Tips - Pangkalan Komputer ID')

@section('content')
<div class="container mx-auto mt-6 p-4">
    <header class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Artikel, Tips & Trik</h1>
        <p class="text-gray-600">Informasi terbaru seputar teknologi dan perawatan komputer dari kami.</p>
    </header>

    @if (request('search'))
        <div class="mb-6 text-center">
            <p class="text-lg text-gray-700">Hasil pencarian untuk: <strong class="font-semibold">{{ request('search') }}</strong></p>
            <a href="{{ route('articles.index-public') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat semua artikel</a>
        </div>
    @endif

    <div class="mb-8 max-w-lg mx-auto">
        <form action="{{ route('articles.index-public') }}" method="GET" class="flex">
            <input type="text" name="search"
                   class="w-full border-gray-300 rounded-l-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Cari artikel berdasarkan judul..."
                   value="{{ request('search') }}">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Cari
            </button>
        </form>
    </div>

    @if($articles && $articles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($articles as $article)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col hover:shadow-xl transition-shadow duration-300">
                <a href="{{ route('articles.show-public', $article->slug) }}">
                    @if ($article->featured_image_path)
                        <img src="{{ asset('storage/' . $article->featured_image_path) }}" alt="{{ $article->title }}" class="w-full h-56 object-cover">
                    @else
                        <img src="https://via.placeholder.com/400x250.png?text=Artikel" alt="{{ $article->title }}" class="w-full h-56 object-cover">
                    @endif
                </a>
                <div class="p-6 flex-grow flex flex-col">
                    @if($article->category)
                    <p class="text-xs text-indigo-600 font-semibold uppercase">{{ $article->category->name }}</p>
                    @endif
                    <h3 class="mt-1 text-xl font-semibold text-gray-800">
                        <a href="{{ route('articles.show-public', $article->slug) }}" class="hover:text-indigo-800">{{ $article->title }}</a>
                    </h3>
                    <p class="mt-2 text-gray-600 text-sm flex-grow">{{ $article->excerpt }}</p>
                    <div class="mt-4 flex items-center text-xs text-gray-500">
                        <span>Oleh: {{ $article->author->name ?? 'Admin' }}</span>
                        <span class="mx-2">&bull;</span>
                        <span>{{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : '' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    @else
        <p class="text-gray-500 text-center py-10">
            @if (request('search'))
                Tidak ada artikel yang cocok dengan pencarian Anda.
            @else
                Belum ada artikel yang dipublikasikan.
            @endif
        </p>
    @endif
</div>
@endsection