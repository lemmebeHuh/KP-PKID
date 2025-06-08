<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Komplain untuk Order: ') }} {{ $serviceOrder->service_order_number }}
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

                    <p class="mb-4 text-sm text-gray-600">
                        Kami mohon maaf jika ada aspek layanan kami yang kurang memuaskan. Silakan sampaikan keluhan Anda di bawah ini.
                    </p>

                    <form action="{{ route('pelanggan.service-orders.complaints.store', $serviceOrder->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subjek Komplain</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="mt-1 block w-full" required placeholder="Contoh: Masalah masih sama setelah servis">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Lengkap Komplain</label>
                            <textarea name="description" id="description" rows="6" class="mt-1 block w-full" required placeholder="Jelaskan detail keluhan Anda...">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                Kirim Komplain
                            </button>
                            <a href="{{ route('pelanggan.service-orders.show', $serviceOrder->id) }}" class="ml-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>