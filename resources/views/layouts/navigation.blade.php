<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    {{-- Logo mengarah ke dashboard sesuai peran --}}
                    @if(Auth::user()->role->name === 'Admin')
                        <a href="{{ route('admin.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    @elseif(Auth::user()->role->name === 'Teknisi')
                         <a href="{{ route('teknisi.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    @else
                        <a href="{{ route('pelanggan.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    @endif
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    {{-- =================== MENU UNTUK ADMIN =================== --}}
                    @if (Auth::user()->role->name === 'Admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('Dashboard') }}</x-nav-link>
                        <x-nav-link :href="route('admin.service-orders.index')" :active="request()->routeIs('admin.service-orders.*')">{{ __('Order Servis') }}</x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">{{ __('Pengguna') }}</x-nav-link>
                        {{-- <x-nav-link :href="route('home')" target="_blank">Lihat Situs</x-nav-link> --}}
                        
                        {{-- Dropdown Manajemen Konten --}}
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger"><button class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.products.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.articles.*') ? 'border-indigo-400' : 'border-transparent' }} text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"><div>Konten</div><div class="ml-1"><svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div></button></x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.categories.index')">{{ __('Kategori') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.products.index')">{{ __('Produk') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.services.index')">{{ __('Jasa Servis') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.articles.index')">{{ __('Artikel') }}</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                        {{-- Dropdown Feedback --}}
                         <div class="hidden sm:flex sm:items-center sm:ml-2">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger"><button class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.reviews.*') || request()->routeIs('admin.complaints.*') ? 'border-indigo-400' : 'border-transparent' }} text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"><div>Feedback</div><div class="ml-1"><svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div></button></x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.reviews.index')">{{ __('Ulasan Pelanggan') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.complaints.index')">{{ __('Komplain Pelanggan') }}</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                        <x-nav-link :href="route('home')">{{ __('Lihat Situs') }}</x-nav-link>
                        
                        

                    {{-- =================== MENU UNTUK TEKNISI =================== --}}
                    @elseif (Auth::user()->role->name === 'Teknisi')
                    <x-nav-link :href="route('teknisi.dashboard')" :active="request()->routeIs('teknisi.dashboard')">{{ __('Dashboard Tugas') }}</x-nav-link>
                    <x-nav-link :href="route('home')">{{ __('Lihat Situs') }}</x-nav-link>
                    {{-- Tambahkan menu lain untuk Teknisi jika ada --}}
                    
                    {{-- =================== MENU UNTUK PELANGGAN =================== --}}
                    @elseif (Auth::user()->role->name === 'Pelanggan')
                    <x-nav-link :href="route('pelanggan.dashboard')" :active="request()->routeIs('pelanggan.dashboard')">{{ __('Riwayat Servis') }}</x-nav-link>
                    <x-nav-link :href="route('pelanggan.complaints.index')" :active="request()->routeIs('pelanggan.complaints.index')">{{ __('Komplain Saya') }}</x-nav-link>
                    <x-nav-link :href="route('home')">{{ __('Lihat Situs') }}</x-nav-link>
                        {{-- Tambahkan menu lain untuk Pelanggan jika ada --}}
                    @endif
                </div>
            </div>

            
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="ml-3 relative">
    <x-dropdown align="right" width="80"> {{-- Lebar bisa disesuaikan, misal 80 atau 96 --}}
        <x-slot name="trigger">
            <button class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                {{-- Ikon Lonceng --}}
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>

                {{-- Counter Notifikasi Baru --}}
                @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                    <span class="absolute top-1 right-1 block h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                @endif
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="px-4 py-2 text-sm text-gray-700 font-semibold border-b flex justify-between items-center">
                <span>Notifikasi</span>
                {{-- Link untuk menandai semua sudah dibaca bisa ditambahkan nanti --}}
                {{-- <a href="#" class="text-xs text-indigo-600 hover:underline">Tandai semua dibaca</a> --}}
            </div>
            <div class="max-h-96 overflow-y-auto">
                @forelse($notifications as $notification)
                    <a href="{{ $notification->data['url'] ?? '#' }}" 
                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 {{ $notification->read_at ? '' : 'font-bold bg-indigo-50' }}">
                        {{ $notification->data['message'] }}
                        <span class="block text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</span>
                    </a>
                @empty
                    <div class="px-4 py-3 text-sm text-gray-500 text-center">Tidak ada notifikasi.</div>
                @endforelse
            </div>
            {{-- Link ke halaman semua notifikasi bisa ditambahkan nanti --}}
            {{-- <div class="px-4 py-2 text-xs text-center border-t">
                <a href="#" class="text-indigo-600 hover:underline">Lihat Semua Notifikasi</a>
            </div> --}}
        </x-slot>
    </x-dropdown>
</div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger"><button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"><div>{{ Auth::user()->name }}</div><div class="ml-1"><svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div></button></x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">@csrf<x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link></form>
                    </x-slot>
                </x-dropdown>
                
            </div>
            

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"><svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- =================== MENU MOBILE UNTUK ADMIN =================== --}}
            @if (Auth::user()->role->name === 'Admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.service-orders.index')" :active="request()->routeIs('admin.service-orders.*')">{{ __('Order Servis') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">{{ __('Pengguna') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('home')" >{{ __('Lihat Situs') }}</x-responsive-nav-link>
                <div class="pt-2 pb-1 border-t border-gray-200"><div class="px-4"><div class="font-medium text-base text-gray-800">Manajemen Konten</div></div><div class="mt-1 space-y-1"><x-responsive-nav-link :href="route('admin.categories.index')">{{ __('Kategori') }}</x-responsive-nav-link><x-responsive-nav-link :href="route('admin.products.index')">{{ __('Produk') }}</x-responsive-nav-link><x-responsive-nav-link :href="route('admin.services.index')">{{ __('Jasa Servis') }}</x-responsive-nav-link><x-responsive-nav-link :href="route('admin.articles.index')">{{ __('Artikel') }}</x-responsive-nav-link></div></div>
                <div class="pt-2 pb-1 border-t border-gray-200"><div class="px-4"><div class="font-medium text-base text-gray-800">Feedback</div></div><div class="mt-1 space-y-1"><x-responsive-nav-link :href="route('admin.reviews.index')">{{ __('Ulasan Pelanggan') }}</x-responsive-nav-link><x-responsive-nav-link :href="route('admin.complaints.index')">{{ __('Komplain Pelanggan') }}</x-responsive-nav-link></div></div>
                
                {{-- =================== MENU MOBILE UNTUK TEKNISI =================== --}}
                @elseif (Auth::user()->role->name === 'Teknisi')
                <x-responsive-nav-link :href="route('teknisi.dashboard')" :active="request()->routeIs('teknisi.dashboard')">{{ __('Dashboard Tugas') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('home')" >{{ __('Lihat Situs') }}</x-responsive-nav-link>
                
                {{-- =================== MENU MOBILE UNTUK PELANGGAN =================== --}}
                @elseif (Auth::user()->role->name === 'Pelanggan')
                <x-responsive-nav-link :href="route('pelanggan.dashboard')" :active="request()->routeIs('pelanggan.dashboard')">{{ __('Riwayat Servis') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pelanggan.complaints.index')" :active="request()->routeIs('pelanggan.complaints.index')">{{ __('Komplain Saya') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('home')" >{{ __('Lihat Situs') }}</x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4"><div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div><div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div></div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">@csrf<x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link></form>
            </div>
        </div>
    </div>
</nav>