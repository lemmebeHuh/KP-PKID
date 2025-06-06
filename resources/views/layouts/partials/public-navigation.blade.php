<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img class="h-auto w-14" src="{{ asset('images/pkid-logo.png') }}" alt="Pangkalan Komputer ID">
                </a>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200' }} px-3 py-2 rounded-md text-sm font-medium">Beranda</a>
                        <a href="{{ route('products.catalog') }}" class="{{ request()->routeIs('products.catalog') || request()->routeIs('products.show-public') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200' }} px-3 py-2 rounded-md text-sm font-medium">Produk</a>
                        <a href="{{ route('services.catalog') }}" class="{{ request()->routeIs('services.catalog') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200' }} px-3 py-2 rounded-md text-sm font-medium">Layanan</a>
                        <a href="{{ route('articles.index-public') }}" class="{{ request()->routeIs('articles.index-public') || request()->routeIs('articles.show-public') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200' }} px-3 py-2 rounded-md text-sm font-medium">Artikel</a>
                        <a href="{{ route('tracking.form') }}" class="{{ request()->routeIs('tracking.form') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200' }} px-3 py-2 rounded-md text-sm font-medium">Lacak Servis</a>
                        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200' }} px-3 py-2 rounded-md text-sm font-medium">Tentang Kami</a>
                        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200' }} px-3 py-2 rounded-md text-sm font-medium">Kontak</a>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    @if (Route::has('login'))
                        @auth
                            @if(Auth::user()->role->name == 'Admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-700 underline">Dashboard Admin</a>
                            @elseif(Auth::user()->role->name == 'Teknisi')
                                <a href="{{ route('teknisi.dashboard') }}" class="text-sm text-gray-700 underline">Dashboard Teknisi</a>
                            @else
                                <a href="{{ route('pelanggan.dashboard') }}" class="text-sm text-gray-700 underline">Dashboard Saya</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>