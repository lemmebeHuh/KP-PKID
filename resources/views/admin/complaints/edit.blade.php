<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Komplain: ') }} {{ Str::limit($complaint->subject, 40) }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-semibold">Detail Komplain</h3>
                        <p><strong>Pelanggan:</strong> {{ $complaint->customer->name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $complaint->customer->email ?? 'N/A' }}</p>
                        @if($complaint->serviceOrder)
                        <p><strong>No. Order Servis Terkait:</strong> 
                            <a href="{{ route('admin.service-orders.show', $complaint->serviceOrder->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $complaint->serviceOrder->service_order_number }}
                            </a>
                        </p>
                        @endif
                        <p><strong>Tanggal Komplain:</strong> {{ $complaint->created_at->translatedFormat('d M Y, H:i') }}</p>
                        <p><strong>Subjek:</strong> {{ $complaint->subject }}</p>
                        <div class="mt-2">
                            <p class="font-medium">Deskripsi Komplain:</p>
                            <p class="whitespace-pre-wrap bg-gray-50 p-3 rounded-md">{{ $complaint->description }}</p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Oops! Ada yang salah:</strong>
                            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.complaints.update', $complaint->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Update Status Komplain</label>
                            <select name="status" id="status" class="mt-1 block w-full" required>
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $complaint->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="resolved_notes" class="block text-sm font-medium text-gray-700">Catatan Tindak Lanjut / Penyelesaian</label>
                            <textarea name="resolved_notes" id="resolved_notes" rows="4" class="mt-1 block w-full">{{ old('resolved_notes', $complaint->resolved_notes) }}</textarea>
                        </div>

                        <div>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Update Komplain
                            </button>
                            <a href="{{ route('admin.complaints.index') }}" class="ml-4">Kembali ke Daftar Komplain</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>