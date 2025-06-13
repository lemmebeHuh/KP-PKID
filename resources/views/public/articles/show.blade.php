<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - Pangkalan Komputer ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    @include('layouts.partials.public-navigation')

    <div class="container mx-auto mt-6 p-4">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-xl overflow-hidden">
            @if ($article->featured_image_path)
                <img class="w-full h-auto max-h-96 object-cover" src="{{ asset('storage/' . $article->featured_image_path) }}" alt="{{ $article->title }}">
            @endif
            <div class="p-6 sm:p-8">
                @if($article->category)
                <p class="text-sm text-primary font-semibold uppercase">{{ $article->category->name }}</p>
                @endif
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2">{{ $article->title }}</h1>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <span>Oleh: <strong>{{ $article->author->name ?? 'Admin' }}</strong></span>
                    <span class="mx-3">|</span>
                    <span>Dipublikasikan pada: {{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : '' }}</span>
                </div>

                <hr class="my-6">
                <div class="prose lg:prose-xl max-w-none text-gray-700 leading-relaxed whitespace-pre-wrap">
                    {{ $article->content }}
                </div>
                 <div class="mt-8 text-center">
                    <a href="{{ route('articles.index-public') }}" class="text-sm text-primary hover:text-primary-dark">&larr; Kembali ke Daftar Artikel</a>
                </div>
            </div>
        </div>

        {{-- Opsional: Bagian Artikel Terbaru Lainnya --}}
        @if(isset($latestArticles) && $latestArticles->count() > 0)
        <div class="max-w-4xl mx-auto mt-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Baca Juga Artikel Lainnya</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($latestArticles as $latest)
                <div class="bg-white rounded-lg shadow flex flex-col">
                    <div class="p-4 flex-grow">
                        @if($latest->category)
                        <p class="text-xs text-primary font-semibold uppercase">{{ $latest->category->name }}</p>
                        @endif
                        <h4 class="font-semibold text-gray-800 mt-1">
                            <a href="{{ route('articles.show-public', $latest->slug) }}" class="hover:text-primary-dark">{{ $latest->title }}</a>
                        </h4>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @include('layouts.partials.public-footer')
</body>
</html>