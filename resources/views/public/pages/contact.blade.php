@extends('layouts.public')

@section('title', 'Kontak Kami - Pangkalan Komputer ID')

@section('content')
<div class="bg-white">
    {{-- ================================== --}}
    {{--         1. HEADER HALAMAN          --}}
    {{-- ================================== --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-gray-900 via-primary-dark to-gray-800 animate-gradient-xy">
        {{-- Elemen Latar Belakang (Opsional) --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gray-500 mix-blend-overlay"></div>
        </div>

        {{-- Konten Utama Hero Section --}}
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <div class="text-center">
                {{-- Kita gunakan style="animation-fill-mode:backwards;" agar elemen tersembunyi sebelum animasi dimulai --}}
                {{-- dan animation-delay untuk membuat animasi muncul berurutan --}}
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl animate-fade-in-up" style="animation-fill-mode: backwards; animation-delay: 0.2s;">
                    Kami Siap Membantu Anda
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-300 animate-fade-in-up" style="animation-fill-mode: backwards; animation-delay: 0.1s;">
                    Punya pertanyaan tentang layanan atau produk kami? Tim kami siap memberikan informasi yang Anda butuhkan.
                </p>
                
            </div>
        </div>
    </div>

    {{-- ================================== --}}
    {{--    2. INFORMASI KONTAK (KARTU)     --}}
    {{-- ================================== --}}
    <div class="container mx-auto px-6 lg:px-8 py-16 sm:py-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Kartu Alamat --}}
            <div class="text-center p-6 border border-gray-200 rounded-lg">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-primary">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Alamat Workshop</h3>
                <p class="mt-1 text-gray-600 text-sm">
                    Jl. Sersan Sodik No.57 RT03/RW.2, Gg. Kelinci VI, Sukasari, Kota Bandung, Jawa Barat 40154
                </p>
            </div>
            {{-- Kartu Email --}}
            <div class="text-center p-6 border border-gray-200 rounded-lg">
                 <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-primary">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Email</h3>
                <a href="mailto:kontak@pangkalan_komputer.id" class="mt-1 text-primary hover:underline text-sm">kontak@pangkalan_komputer.id</a>
            </div>
            {{-- Kartu WhatsApp --}}
            <div class="text-center p-6 border border-gray-200 rounded-lg">
                 <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-primary">
                    <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.358 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">WhatsApp</h3>
                <a href="https://wa.me/6283192310040" target="_blank" class="mt-1 text-primary hover:underline text-sm">+62 831-9231-0040</a>
            </div>
        </div>
    </div>

    {{-- ================================== --}}
    {{--       3. FORM KONTAK & PETA        --}}
    {{-- ================================== --}}
    <div class="bg-gray-50 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:max-w-none grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-16">
                {{-- Kolom Kiri: Form Kontak --}}
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900">Kirimkan Pesan</h2>
                    <p class="mt-4 text-lg text-gray-600">
                        Isi form di bawah ini dan tim kami akan segera menghubungi Anda kembali.
                    </p>
                    @if (session('success'))
                        <div class="mt-6 rounded-md bg-green-50 p-4"><div class="flex"><div class="flex-shrink-0"><svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg></div><div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('success') }}</p></div></div></div>
                    @endif
                    @if ($errors->any())
                        <div class="mt-6 rounded-md bg-red-50 p-4"><div class="flex"><div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg></div><div class="ml-3"><h3 class="text-sm font-medium text-red-800">Harap periksa kembali input Anda</h3></div></div></div>
                    @endif
                    <form action="{{ route('contact.send') }}" method="POST" class="mt-6 space-y-6">
                        @csrf
                        <div><label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label><input type="text" name="name" id="name" required value="{{ old('name') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary focus:border-primary"></div>
                        <div><label for="email" class="block text-sm font-medium text-gray-700">Email</label><input id="email" name="email" type="email" required value="{{ old('email') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary focus:border-primary"></div>
                        <div><label for="subject" class="block text-sm font-medium text-gray-700">Subjek</label><input type="text" name="subject" id="subject" required value="{{ old('subject') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary focus:border-primary"></div>
                        <div><label for="message" class="block text-sm font-medium text-gray-700">Pesan Anda</label><textarea id="message" name="message" rows="4" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-primary focus:border-primary">{{ old('message') }}</textarea></div>
                        <div><button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">Kirim Pesan</button></div>
                    </form>
                </div>
                {{-- Kolom Kanan: Peta --}}
                <div class="w-full h-80 lg:h-full rounded-lg overflow-hidden shadow-lg">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.369512788002!2d107.5977952!3d-6.846230200000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e1a54132f539%3A0x9a18285e83cd1c6a!2sPangkalan%20Komputer%20ID!5e0!3m2!1sid!2sid!4v1749182839506!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection