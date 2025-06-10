@extends('layout')

@section('content')
<div class="max-w-md mx-auto p-4 bg-gray-50 min-h-screen">
    <div class="flex items-center mb-6">
        <button class="mr-2 text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h1 class="text-xl font-bold flex-grow text-center text-gray-800">Pilih Tanggal & Waktu</h1>
        <div class="w-6"></div> </div>

    <div class="bg-white p-4 rounded-xl shadow-md mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">May 2025</h2>
        
        <div class="grid grid-cols-5 gap-2">
            @foreach ($dates as $index => $date)
            <div class="@if($selectedDate == $index) bg-gradient-to-b from-yellow-400 to-orange-500 text-white shadow-lg @else text-gray-700 bg-gray-100 hover:bg-gray-200 @endif rounded-lg py-2 text-center cursor-pointer transition-all duration-300"
                 onclick="window.location='?date={{ $index }}'">
                <div class="text-lg font-bold">{{ $date['day'] }}</div>
                <div class="text-sm">{{ $date['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-md mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Waktu yang Tersedia</h2>
        <div class="grid grid-cols-3 gap-3">
            @foreach ($times as $time)
            <div class="@if($selectedTime == $time) bg-gradient-to-r from-cyan-400 to-blue-600 text-white shadow-lg @else bg-blue-50 text-blue-900 hover:bg-blue-100 @endif
                         text-sm font-semibold text-center py-3 rounded-xl cursor-pointer transition-all duration-300"
                 onclick="window.location='?date={{ $selectedDate }}&time={{ urlencode($time) }}'">
                {{ $time }}
            </div>
            @endforeach
        </div>
    </div>

    <button class="w-full bg-gradient-to-r from-cyan-400 to-blue-600 text-white font-bold py-3 rounded-xl shadow-md hover:opacity-90 transition">
        Konfirmasi
    </button>
</div>
@endsection