{{-- Anda bisa menggunakan layout publik yang berbeda, atau extends layout app jika sesuai --}}
{{-- Untuk contoh ini, saya buat sederhana tanpa layout Breeze --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Servis - Pangkalan Komputer ID</title>
    {{-- Tambahkan link ke CSS Anda, misalnya Tailwind jika Anda pakai di publik --}}
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Jika menggunakan Vite --}}
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto mt-10 p-4 max-w-md">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <div class="text-center mb-6">
                {{-- Ganti dengan logo Anda --}}
                <img src="{{ asset('images/logo_pangkalan_komputer.png') }}" alt="Logo Pangkalan Komputer ID" class="mx-auto h-16 mb-2">
                <h1 class="text-2xl font-semibold text-gray-700">Lacak Status Servis Anda</h1>
                <p class="text-gray-500 text-sm">Masukkan Nomor Order Servis untuk melihat progres perbaikan.</p>
            </div>

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('tracking.result') }}" method="GET">
                <div class="mb-4">
                    <label for="service_order_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Order Servis</label>
                    <input type="text" name="service_order_number" id="service_order_number" value="{{ old('service_order_number', request()->get('service_order_number')) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                           placeholder="Contoh: PK-20240605-ABCD" required>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
                    Lacak Servis
                </button>
            </form>
        </div>
         <p class="text-center text-sm text-gray-500 mt-4">&copy; {{ date('Y') }} Pangkalan Komputer ID</p>
    </div>
</body>
</html> 