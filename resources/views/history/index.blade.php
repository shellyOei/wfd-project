@extends('layout')

@section('content')
<div class="max-w-md mx-auto p-4 bg-[#F4F4FD] min-h-screen pb-20">
    {{-- Search Bar --}}
    <div class= "rounded-xl shadow-md relative mb-4">
        <form action="{{ route('history.index') }}" method="GET">
            <input type="text" name="search" placeholder="Temukan Dokter" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-search text-gray-400"></i>
            </div>
        </form>
    </div>

    {{-- Filter Buttons --}}
    <div class="flex items-center gap-x-3 mb-4">
        <a href="{{ route('history.index', ['search' => request('search')]) }}" class=" shadow-md flex-1 text-center px-6 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-white text-gray-600' }}">Semua</a>
        <a href="{{ route('history.index', ['status' => 'completed', 'search' => request('search')]) }}" class="shadow-md flex-1 text-center px-6 py-2 rounded-lg text-sm font-medium {{ request('status') == 'completed' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600' }}">Selesai</a>
        <a href="{{ route('history.index', ['status' => 'canceled', 'search' => request('search')]) }}" class="shadow-md flex-1 text-center px-6 py-2 rounded-lg text-sm font-medium {{ request('status') == 'canceled' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600' }}">Dibatalkan</a>
    </div>

    {{-- Time Filter Dropdown --}}
    <div class="mb-4">
        <form action="{{ route('history.index') }}" method="GET" id="timeFilterForm">
            {{-- Hidden input untuk mempertahankan filter lain --}}
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">

            {{-- Kembali menggunakan w-full, tapi mempertahankan text-sm --}}
            <select name="time_filter" onchange="this.form.submit()" class="w-full text-sm bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Waktu</option>
                <option value="today" {{ request('time_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="month" {{ request('time_filter') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="year" {{ request('time_filter') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
            </select>
        </form>
    </div>
    {{-- Appointment List --}}
    <div class="space-y-3">
        @forelse ($appointments as $app)
        <a href="{{ route('history.show', $app->id) }}" class="block bg-white p-4 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($app->schedule->Datetime)->translatedFormat('l, d F Y') }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($app->schedule->Datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($app->schedule->Datetime)->addMinutes(30)->format('H:i') }}
                    </p>
                    <p class="mt-2 font-bold text-gray-900">dr. {{ $app->schedule->doctor->name }}</p>
                    <p class="text-xs text-gray-600">{{ $app->schedule->doctor->specialization->name }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if($app->status == 'completed')
                        <span class="text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full">Selesai</span>
                    @elseif($app->status == 'canceled')
                        <span class="text-xs font-semibold text-red-700 bg-red-100 px-3 py-1 rounded-full">Dibatalkan</span>
                    @else
                        <span class="text-xs font-semibold text-yellow-700 bg-yellow-100 px-3 py-1 rounded-full">Pending</span>
                    @endif
                    <i class="fa-solid fa-chevron-right text-gray-400"></i>
                </div>
            </div>
        </a>
        @empty
            <p class="text-center text-gray-500 mt-10">Tidak ada riwayat konsultasi.</p>
        @endforelse
    </div>
</div>
@endsection