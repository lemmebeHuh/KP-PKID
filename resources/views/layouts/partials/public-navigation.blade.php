{{-- Inisialisasi Alpine.js untuk state menu mobile (open/closed) --}}
<nav x-data="{ open: false }" class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- Bagian Kiri: Logo --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}">
                    <img class="h-10 w-auto" src="{{ asset('images/pkid-logo.png') }}" alt="Pangkalan Komputer ID">
                </a>
            </div>

            {{-- Menu Navigasi Desktop (Hanya terlihat di layar medium ke atas) --}}
            <div class="hidden md:ml-6 md:flex md:space-x-8">
                <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('home') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Beranda</a>
                <a href="{{ route('about') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('about') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Tentang Kami</a>
                <a href="{{ route('services.catalog') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('services.catalog*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Layanan</a>
                <a href="{{ route('products.catalog') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('products.catalog*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Produk</a>
                <a href="{{ route('articles.index-public') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('articles.index-public*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Artikel</a>
                <a href="{{ route('contact') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('contact') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Kontak</a>
            </div>

            {{-- Bagian Kanan: Lacak Servis & Tombol Login/Dashboard Desktop --}}
            <div class="hidden md:ml-6 md:flex md:items-center">
                <a href="{{ route('tracking.form') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                    Lacak Servis
                </a>
                <div class="ml-4">
                    @auth
                        @if(Auth::user()->role->name == 'Admin') <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">Dashboard Admin</a>
                        @elseif(Auth::user()->role->name == 'Teknisi') <a href="{{ route('teknisi.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">Dashboard Teknisi</a>
                        @else <a href="{{ route('pelanggan.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">Dashboard Saya</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">Log in</a>
                    @endauth
                </div>
            </div>

            {{-- Tombol Burger Menu untuk Mobile (Hanya terlihat di layar kecil) --}}
            <div class="-mr-2 flex items-center md:hidden">
                <button @click="open = ! open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Buka menu utama</span>
                    {{-- Ikon Burger (saat menu tertutup) --}}
                    <svg x-show="!open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    {{-- Ikon 'X' (saat menu terbuka) --}}
                    <svg x-show="open" class="h-6 w-6" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Panel Menu Mobile (muncul saat 'open' bernilai true) --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="md:hidden" id="mobile-menu" style="display: none;">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Beranda</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Tentang Kami</a>
            <a href="{{ route('services.catalog') }}" class="{{ request()->routeIs('services.catalog*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Layanan</a>
            <a href="{{ route('products.catalog') }}" class="{{ request()->routeIs('products.catalog*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Produk</a>
            <a href="{{ route('articles.index-public') }}" class="{{ request()->routeIs('articles.index-public*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Artikel</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Kontak</a>
            <a href="{{ route('tracking.form') }}" class="{{ request()->routeIs('tracking.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Lacak Servis</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-2 space-y-1">
                @auth
                    @if(Auth::user()->role->name == 'Admin') <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Dashboard Admin</a>
                    @elseif(Auth::user()->role->name == 'Teknisi') <a href="{{ route('teknisi.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Dashboard Teknisi</a>
                    @else <a href="{{ route('pelanggan.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Dashboard Saya</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">@csrf<a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Log Out</a></form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Log in</a>
                    @if (Route::has('register'))
                       <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Register</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>