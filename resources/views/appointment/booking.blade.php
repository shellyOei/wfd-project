@extends('layout')

@section('content')
<div class="max-w-md mx-auto p-4 bg-gray-50 min-h-screen">
    <div class="flex items-center mb-6">
        <a href="{{ url()->previous() }}" class="mr-2 text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-xl font-bold flex-grow text-center text-gray-800">Pilih Tanggal & Waktu</h1>
        <div class="w-6"></div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-md mb-6">
        {{-- Display current month and year dynamically --}}
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
        </h2>

        <div class="flex overflow-x-auto pb-4 custom-scrollbar"> {{-- Added custom-scrollbar class --}}
            @php
                // Generate dates for the next 7 days, including today
                $bookingDates = [];
                for ($i = 0; $i < 7; $i++) {
                    $date = \Carbon\Carbon::today()->addDays($i);
                    $bookingDates[] = [
                        'day' => $date->translatedFormat('D'), // e.g., Rab, Kam (Localized Day Abbreviation)
                        'date_num' => $date->format('j'), // e.g., 1, 15 (Day of the month without leading zeros)
                        'full_date' => $date->format('Y-m-d'), // for URL parameter and comparison
                    ];
                }
            @endphp

            @foreach ($bookingDates as $index => $dateItem)
                <div class="flex-none w-20 mx-1
                            @if(isset($selectedDate) && $selectedDate == $dateItem['full_date']) bg-gradient-to-b from-yellow-400 to-orange-500 text-white shadow-lg
                            @else text-gray-700 bg-gray-100 hover:bg-gray-200 @endif
                            rounded-lg py-2 text-center cursor-pointer transition-all duration-300"
                    onclick="window.location='?date={{ $dateItem['full_date'] }}'">
                    <div class="text-lg font-bold">{{ $dateItem['day'] }}</div>
                    <div class="text-sm">{{ $dateItem['date_num'] }}</div>
                </div>
            @endforeach
        </div>

        <p class="text-sm text-gray-500 mt-4 text-center">
            <strong class="text-blue-600">Info:</strong> Booking tersedia hingga 7 hari ke depan.
        </p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-md mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Waktu yang Tersedia</h2>
        <div class="grid grid-cols-3 gap-3">
            @foreach ($times as $time)
            <div class="@if(isset($selectedTime) && $selectedTime == $time) bg-gradient-to-r from-cyan-400 to-blue-600 text-white shadow-lg @else bg-blue-50 text-blue-900 hover:bg-blue-100 @endif
                          text-sm font-semibold text-center py-3 rounded-xl cursor-pointer transition-all duration-300"
                  onclick="window.location='?date={{ $selectedDate ?? '' }}&time={{ urlencode($time) }}'">
                {{ $time }}
            </div>
            @endforeach
        </div>
    </div>

    <button class="w-full bg-gradient-to-r from-cyan-400 to-blue-600 text-white font-bold py-3 rounded-xl shadow-md hover:opacity-90 transition">
        Konfirmasi
    </button>
</div>

{{-- Add custom CSS for the scrollbar if you want to style it --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px; /* height of horizontal scrollbar */
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #a0aec0; /* color of the scrollbar thumb */
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background-color: #edf2f7; /* color of the scrollbar track */
        border-radius: 3px;
    }
</style>
@endsection