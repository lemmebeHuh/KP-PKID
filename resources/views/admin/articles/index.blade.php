<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manajemen Artikel') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- ... (Notifikasi Sukses/Error) ... --}}
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('admin.articles.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Tulis Artikel Baru
                        </a>
                    </div>
                    <div class="overflow-x-auto border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penulis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Publikasi</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($articles as $article)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Str::limit($article->title, 50) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $article->author->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $article->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($article->status) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $article->published_at ? $article->published_at->translatedFormat('d M Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger"><button class="inline-flex items-center px-2 py-1"><svg class="w-5 h-5 text-gray-500 hover:text-gray-700" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg></button></x-slot>
                                                <x-slot name="content">
                                                    <a href="{{ route('articles.show-public', $article->slug) }}" target="_blank" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100">Lihat di Publik</a>
                                                    <x-dropdown-link :href="route('admin.articles.edit', $article->id)">Edit</x-dropdown-link>
                                                    <div class="border-t border-gray-100"></div>
                                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Yakin hapus artikel ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm leading-5 text-red-700 hover:bg-gray-100">Hapus</button>
                                                    </form>
                                                </x-slot>
                                            </x-dropdown>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada artikel.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $articles->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>