@extends('layouts.public')

@section('title', 'Kontak Kami - Pangkalan Komputer ID')

@section('content')
<div class="bg-white">
    {{-- Header --}}
    <div class="py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-indigo-600">Hubungi Kami</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Kami Siap Membantu Anda</p>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Punya pertanyaan, butuh konsultasi, atau ingin servis perangkat Anda? Jangan ragu untuk menghubungi kami melalui informasi di bawah ini atau kirimkan pesan melalui form.
                </p>
            </div>
        </div>
    </div>

    {{-- Konten Utama: Info Kontak, Peta, dan Form --}}
    <div class="mx-auto max-w-7xl px-6 lg:px-8 pb-24 sm:pb-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-16">
            {{-- Kolom Kiri: Info Kontak & Peta --}}
            <div>
                <h3 class="text-2xl font-semibold text-gray-800">Informasi Kontak</h3>
                <div class="mt-4 space-y-4 text-gray-600">
                    <div class="flex gap-x-3">
                        <svg class="h-6 w-6 flex-none text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18h18a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0119.5 18.75H2.25A2.25 2.25 0 010 16.5V4.5A2.25 2.25 0 012.25 3z" /></svg>
                        <span>l. Sersan Sodik No.57 RT03, RW.2, Gg. Kelinci VI, Kec. Sukasari, Kota Bandung, Jawa Barat 40154</span>
                    </div>
                    <div class="flex gap-x-3">
                        <svg class="h-6 w-6 flex-none text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                        <span><a href="https://wa.me/6283192310040" class="hover:text-indigo-700">+62 812-7364-7463</a> (WhatsApp/Telepon)</span>
                    </div>
                    <div class="flex gap-x-3">
                        <svg class="h-6 w-6 flex-none text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        <span><a href="mailto:contact@pk.id" class="hover:text-indigo-700">contact@pk.id</a></span>
                    </div>
                </div>
                <div class="mt-8 rounded-lg overflow-hidden shadow-lg">
                    {{-- Ganti src dengan link embed Google Maps lokasi Anda --}}
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.369512788002!2d107.5977952!3d-6.846230200000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e1a54132f539%3A0x9a18285e83cd1c6a!2sPangkalan%20Komputer%20ID!5e0!3m2!1sid!2sid!4v1749182839506!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            {{-- Kolom Kanan: Form Kontak --}}
            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Kirim Pesan</h3>

                @if (session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        <p class="font-bold">Pesan Terkirim</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <p class="font-bold">Harap periksa kembali input Anda:</p>
                        <ul>@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Anda</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Anda</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subjek</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="mt-1 block w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700">Pesan</label>
                        <textarea name="message" id="message" rows="5" class="mt-1 block w-full" required>{{ old('message') }}</textarea>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-md">
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection