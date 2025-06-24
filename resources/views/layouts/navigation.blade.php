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
    <x-dropdown align="right" width="96">
        <x-slot name="trigger">
            <button class="relative inline-flex items-center p-2 rounded-full text-gray-500 hover:text-gray-700 focus:outline-none focus:bg-gray-100 transition">
                {{-- Ikon Lonceng --}}
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                
                {{-- Counter Notifikasi Baru --}}
                {{-- @if(isset($unreadNotifications) && $unreadNotifications->count() > 0) --}}
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="absolute top-1 right-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                @endif
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="px-4 py-2 font-bold border-b">Notifikasi</div>
            
            <div class="max-h-80 overflow-y-auto">
                @forelse(Auth::user()->notifications()->take(10)->get() as $notification)
                    @php
                        $iconClass = 'text-gray-400';
                        $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'; // Default Icon (Info)
                        
                        switch ($notification->data['type'] ?? '') {
                            case 'new_review':
                                $iconClass = 'text-yellow-500';
                                $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345h5.364c.518 0 .734.665.33 1.01l-4.343 3.16a.563.563 0 00-.182.635l2.125 5.111a.563.563 0 01-.84.62l-4.343-3.16a.563.563 0 00-.654 0l-4.343 3.16a.563.563 0 01-.84-.62l2.125-5.111a.563.563 0 00-.182-.635l-4.343-3.16a.563.563 0 01.33-1.01h5.364a.563.563 0 00.475-.345L11.48 3.5z" />'; // Star Icon
                                break;
                            case 'new_complaint':
                                $iconClass = 'text-red-500';
                                $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />'; // Warning Icon
                                break;
                            case 'status_update':
                                $iconClass = 'text-blue-500';
                                $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.667 0l3.181-3.183m-4.991-2.695v.001" />'; // Arrow Path Icon
                                break;
                            case 'quotation_response':
                                $iconClass = 'text-green-500';
                                $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-2.138a.563.563 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />'; // Chat Bubble Icon
                                break;
                        }
                    @endphp

                    <a href="{{ route('notifications.read', $notification->id) }}" 
                       class="flex items-start px-4 py-3 hover:bg-gray-100 {{ $notification->read_at ? 'text-gray-500' : 'font-bold bg-blue-50 text-gray-800' }}">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 {{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                {!! $iconPath !!}
                            </svg>
                        </div>
                        <div class="ml-3 w-full">
                            <p class="text-sm leading-snug">{{ $notification->data['message'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-8 text-sm text-gray-500 text-center">Tidak ada notifikasi.</div>
                @endforelse
            </div>
            <div class="px-4 py-2 text-xs text-center border-t">
            </div>
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

