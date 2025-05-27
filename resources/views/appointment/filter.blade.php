@extends('layout')

@section('content')
<div class="max-w-md mx-auto p-4">
    <!-- Header -->
    <div class="flex items-center mb-4">
        <button class="mr-2 text-gray-600">
            <!-- Back Icon -->
            ←
        </button>
        <h1 class="text-lg font-semibold flex-grow text-center">Dokter</h1>
        <div class="w-6"></div> <!-- spacer for symmetry -->
    </div>

    <!-- Search Bar -->
    <div class="mb-4">
        <input
            type="text"
            placeholder="Temukan Dokter"
            class="w-full rounded-full px-4 py-2 bg-gray-100 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring focus:border-blue-400"
        />
    </div>

    <!-- Pilih Spesialis -->
    <h2 class="text-sm font-medium mb-2">Pilih Spesialis</h2>

    <div class="grid grid-cols-3 gap-4">
        @foreach (range(1, 6) as $i)
        <div class="flex flex-col items-center justify-center p-4 rounded-xl shadow bg-white">
            <img src="{{ asset('icons/heart-line.svg') }}" alt="icon" class="w-10 h-10 mb-2">
            <span class="text-sm text-blue-600 font-semibold">Kardiologi</span>
        </div>
        @endforeach
    </div>
</div>
@endsection
