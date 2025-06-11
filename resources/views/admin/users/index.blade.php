<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
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
                    @if (session('error'))
                         <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p class="font-bold">Error</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- Header Tabel: Tombol Tambah dan Filter/Search --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                        {{-- Tombol Tambah --}}
                        <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Tambah Pengguna Baru
                        </a>
                        {{-- Placeholder untuk Search/Filter Pengguna --}}
                        <div class="w-full sm:w-auto mt-4 sm:mt-0">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex">
        <input type="text" name="search" class="w-full sm:w-64 border-gray-300 rounded-l-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Cari nama atau email..." value="{{ request('search') }}">
        <button type="submit" class="p-2 bg-indigo-600 text-white rounded-r-lg hover:bg-indigo-700">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
        </button>
    </form>
</div>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="overflow-x-auto border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Bergabung</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    {{-- Placeholder untuk foto profil, jika ada --}}
                                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-200">
                                                        <span class="font-medium text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $user->phone_number ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($user->role)
                                                @php
                                                    $roleColor = 'bg-gray-100 text-gray-800'; // Default
                                                    switch ($user->role->name) {
                                                        case 'Admin':
                                                            $roleColor = 'bg-red-100 text-red-800'; break;
                                                        case 'Teknisi':
                                                            $roleColor = 'bg-yellow-100 text-yellow-800'; break;
                                                        case 'Pelanggan':
                                                            $roleColor = 'bg-green-100 text-green-800'; break;
                                                    }
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleColor }}">
                                                    {{ $user->role->name }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">Tidak ada peran</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- Dropdown Aksi --}}
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button class="inline-flex items-center px-2 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                                    </button>
                                                </x-slot>
                                                <x-slot name="content">
                                                    <x-dropdown-link :href="route('admin.users.edit', $user->id)">Edit Pengguna</x-dropdown-link>

                                                    {{-- Hanya izinkan hapus jika bukan user admin yang sedang login --}}
                                                    @if (Auth::id() !== $user->id)
                                                        <div class="border-t border-gray-100"></div>
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menonaktifkan pengguna ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm leading-5 text-red-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">Nonaktifkan</button>
                                                        </form>
                                                    @endif
                                                </x-slot>
                                            </x-dropdown>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>