<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tulis Artikel Baru') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Oops! Ada yang salah:</strong>
                            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Artikel</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full" required>
                        </div>

                        {{-- Slug bisa dibuat otomatis, jadi kita tidak tampilkan di form untuk kesederhanaan awal --}}
                        {{-- <div class="mb-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="mt-1 block w-full">
                        </div> --}}

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Konten Artikel</label>
                            <textarea name="content" id="content" rows="10" class="mt-1 block w-full">{{ old('content') }}</textarea>
                            {{-- Untuk editor WYSIWYG bisa diintegrasikan nanti --}}
                        </div>

                        <div class="mb-4">
                            <label for="excerpt" class="block text-sm font-medium text-gray-700">Kutipan Singkat (Excerpt - Opsional)</label>
                            <textarea name="excerpt" id="excerpt" rows="3" class="mt-1 block w-full">{{ old('excerpt') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full">
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', 'draft') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="featured_image" class="block text-sm font-medium text-gray-700">Gambar Unggulan (Opsional)</label>
                            <input type="file" name="featured_image" id="featured_image" class="mt-1 block w-full">
                        </div>

                        {{-- Penulis akan diambil dari user yang login, jadi tidak perlu input --}}
                        {{-- Jika ingin ada pilihan penulis: --}}
                        <div class="mb-4">
                        <label for="author_id" class="block text-sm font-medium text-gray-700">Penulis</label>
                        <select name="author_id" id="author_id" class="mt-1 block w-full" required>
                            <option value="">Pilih Penulis</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" {{ old('author_id', Auth::id()) == $author->id ? 'selected' : '' }}> 
                                    {{-- Default ke user login, tapi bisa dipilih --}}
                                    {{ $author->name }} ({{ $author->role->name }})
                                </option>
                            @endforeach
                        </select>
                        </div>
                       

                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan Artikel</button>
                            <a href="{{ route('admin.articles.index') }}" class="ml-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>