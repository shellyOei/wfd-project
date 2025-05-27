@extends('layout')

@section('content')
<div class="max-w-md mx-auto p-4">
    <!-- Header -->
    <div class="flex items-center mb-4">
        <button class="mr-2 text-gray-600">←</button>
        <h1 class="text-lg font-semibold flex-grow text-center">Pilih Tanggal dan Waktu</h1>
        <div class="w-6"></div>
    </div>

    <!-- Pilih Tanggal -->
    <div class="grid grid-cols-5 gap-2 mb-6 bg-white p-3 rounded-xl shadow">
        @foreach ($dates as $index => $date)
        <div class="@if($selectedDate == $index) bg-gradient-to-b from-yellow-400 to-orange-500 text-white @else text-gray-700 @endif rounded-lg py-2 text-center cursor-pointer"
             onclick="window.location='?date={{ $index }}'">
            <div class="text-lg font-bold">{{ $date['day'] }}</div>
            <div class="text-sm">{{ $date['label'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Waktu Tersedia -->
    <h2 class="text-md font-semibold mb-3">Waktu yang Tersedia</h2>
    <div class="grid grid-cols-3 gap-3 mb-6">
        @foreach ($times as $time)
        <div class="@if($selectedTime == $time) bg-gradient-to-r from-cyan-400 to-blue-600 text-white @else bg-blue-50 text-blue-900 @endif
                    text-sm font-semibold text-center py-2 rounded-xl cursor-pointer"
             onclick="window.location='?date={{ $selectedDate }}&time={{ urlencode($time) }}'">
            {{ $time }}
        </div>
        @endforeach
    </div>

    <!-- Tombol Konfirmasi -->
    <button class="w-full bg-gradient-to-r from-cyan-400 to-blue-600 text-white font-bold py-3 rounded-xl shadow-md hover:opacity-90 transition">
        Konfirmasi
    </button>
</div>
@endsection
