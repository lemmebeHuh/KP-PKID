@extends('layouts.public')

@section('title', 'Lacak Servis - Pangkalan Komputer ID')

@section('content')
<div class="flex items-center justify-center min-h-[60vh] bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo Pangkalan Komputer ID" class="mx-auto h-16 mb-2">

            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Lacak Status Servis Anda
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Masukkan Nomor Order Servis Anda di bawah ini untuk melihat progres perbaikan.
            </p>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('tracking.result') }}" method="GET">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="service_order_number" class="sr-only">Nomor Order Servis</label>
                    <input id="service_order_number" name="service_order_number" type="text" required 
                           class="appearance-none rounded-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" 
                           placeholder="Contoh: PK-20240605-ABCD" 
                           value="{{ old('service_order_number', request()->get('service_order_number')) }}">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Lacak Servis
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

