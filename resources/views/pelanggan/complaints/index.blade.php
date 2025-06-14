<x-app-layout>
    <x-slot name="title">
        {{ __('Komplain') }}
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Komplain Anda') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Daftar Komplain yang Telah Anda Ajukan</h3>

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($complaints->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Diajukan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Servis Terkait</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subjek</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan Admin</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($complaints as $complaint)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $complaint->created_at->translatedFormat('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $complaint->serviceOrder ? $complaint->serviceOrder->service_order_number : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $complaint->subject }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($complaint->status == 'Open') bg-red-100 text-red-800 @elseif($complaint->status == 'In Progress') bg-yellow-100 text-yellow-800 @elseif($complaint->status == 'Resolved') bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $complaint->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-pre-wrap">{{ $complaint->resolved_notes ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $complaints->links() }}
                        </div>
                    @else
                        <p>Anda belum pernah mengajukan komplain.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>