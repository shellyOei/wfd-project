@extends('layout')

@section('head')
@endsection

@section('content')
    {{-- <div class="min-h-screen bg-gray-100 px-4 py-6">
         --}}
    <div class="max-w-md mx-auto p-4">
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
            <input type="text" placeholder="Temukan Dokter"
                class="w-full rounded-full px-4 py-2 bg-gray-100 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring focus:border-blue-400" />
        </div>

        <!-- Doctor List -->
        <h2 class="text-lg font-semibold mb-2">List Dokter</h2>

        <div class="space-y-4">
            <!-- Doctor Card -->
            {{-- @foreach ($doctors as $doctor) --}}
            <div class="flex items-center p-4 rounded-xl text-white"
                style="background: linear-gradient(to right, #40ACD8,#41ADD9, #2244C2);">
                <img src="" alt="doctor name" {{-- <img src="{{ $doctor->image }}" alt="{{ $doctor->name }}" --}}
                    class="w-20 h-20 rounded-full object-cover border-2 border-white mr-4" />
                <div class="flex-1">
                    {{-- <h3 class="text-lg font-bold">{{ $doctor->name }}</h3> --}}
                    {{-- <p class="text-sm">{{ $doctor->specialization }}</p>
                <p class="text-xs mt-1">{{ $doctor->description }}</p> --}}

                    <h3 class="text-lg font-bold">nama dokter</h3>
                    <p class="text-sm">spesialis dokter</p>
                    <p class="text-xs mt-1">deskripsi dokter</p>

                    <button class="mt-2 bg-white font-semibold px-4 py-1 rounded-full text-sm hover:bg-gray-100"
                        style="color: #4980FF;">
                        Lihat Detail
                    </button>

                </div>
            </div>
            {{-- @endforeach --}}
        </div>
    </div>
@endsection


@section('script')
@endsection
