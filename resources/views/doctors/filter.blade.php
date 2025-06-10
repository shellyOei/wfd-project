@extends('layout')

@section('content')
<div class="max-w-md mx-auto p-5 bg-gray-50 min-h-screen font-sans antialiased">
    <div class="flex items-center justify-between mb-6">
        <button onclick="history.back()" class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out p-1 -ml-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h1 class="text-2xl font-bold text-gray-800 flex-grow text-center">Dokter</h1>
        <div class="w-7"></div> 
    </div>

    <div class="mb-6">
        <div class="relative">
            <input type="text" placeholder="Temukan Dokter..." class="w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-gray-200 placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-300 ease-in-out shadow-sm" />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-semibold text-gray-700 mb-4">Pilih Spesialis</h2>

    <div class="grid grid-cols-3 gap-4">
        @foreach ($specializations as $specialization)
        <a href="{{ route('doctors.by_specialization', $specialization) }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out cursor-pointer border border-gray-100 transform hover:-translate-y-1">
            <img src="{{ asset($specialization->icon) }}" alt="{{ $specialization->name }} Icon" class="w-12 h-12 mb-2">
            <span class="text-sm text-blue-700 font-semibold text-center mt-1">{{ $specialization->name }}</span>
        </a>
        @endforeach
    </div>
</div>
@endsection