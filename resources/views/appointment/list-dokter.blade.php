@extends('layout')

@section('head')
    {{-- Add any specific meta tags or CSS links here if needed --}}
@endsection

@section('content')
<div class="max-w-md mx-auto p-5 bg-gray-50 min-h-screen font-sans antialiased">

    <div class="flex items-center justify-between mb-6">
        <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out p-1 -ml-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800 flex-grow text-center">Dokter</h1>
        <div class="w-7"></div> {{-- Spacer for symmetry --}}
    </div>

    <div class="mb-6">
        <div class="relative">
            <input
                type="text"
                placeholder="Temukan Dokter..."
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-gray-200 placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-300 ease-in-out shadow-sm"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-semibold text-gray-700 mb-4">List Dokter</h2>

    <div class="space-y-4">
        {{-- Loop through doctors --}}
        {{-- @foreach ($doctors as $doctor) --}}
        {{-- Example Doctor Card (replace with dynamic data when ready) --}}
        <a href="#" class="block"> {{-- Use <a> tag to make the entire card clickable --}}
            <div class="flex items-center p-4 rounded-xl text-white shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1"
                style="background: linear-gradient(to right, #40ACD8, #4980FF, #2244C2);"> {{-- Adjusted middle color to be consistent with previous gradient --}}
                <img src="{{ asset('appointment/doctor.png') }}" alt="Doctor Andrew S.pd" {{-- Placeholder image --}}
                    {{-- <img src="{{ $doctor->image }}" alt="{{ $doctor->name }}" --}}
                    class="w-20 h-20 rounded-full object-cover border-2 border-white mr-4 flex-shrink-0" />
                <div class="flex-1 overflow-hidden"> {{-- Use overflow-hidden to handle long text gracefully --}}
                    {{-- <h3 class="text-lg font-bold truncate">{{ $doctor->name }}</h3> --}}
                    {{-- <p class="text-sm truncate">{{ $doctor->specialization }}</p> --}}
                    {{-- <p class="text-xs mt-1 line-clamp-2">{{ $doctor->description }}</p> --}}

                    <h3 class="text-lg font-bold truncate">Dr. Andrew S.pd</h3>
                    <p class="text-sm truncate opacity-90">Spesialis Jantung, Spesialis Ortho</p>
                    <p class="text-xs mt-1 line-clamp-2 opacity-80">
                        Seorang dokter gigi berpengalaman dengan lebih dari 10 tahun praktik. Saya mengkhususkan diri dalam kedokteran gigi umum dan menawarkan berbagai layanan.
                    </p>

                    <button class="mt-3 bg-white text-blue-600 font-semibold px-4 py-2 rounded-full text-sm hover:bg-gray-100 transition duration-300 ease-in-out shadow-sm">
                        Lihat Detail
                    </button>
                </div>
            </div>
        </a>
        {{-- End Example Doctor Card --}}

        {{-- Duplicate for visual representation --}}
        <a href="#" class="block">
            <div class="flex items-center p-4 rounded-xl text-white shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1"
                style="background: linear-gradient(to right, #6EE7B7, #34D399, #059669);"> {{-- Example with a different gradient --}}
                <img src="https://via.placeholder.com/80" alt="Dr. Sarah"
                    class="w-20 h-20 rounded-full object-cover border-2 border-white mr-4 flex-shrink-0" />
                <div class="flex-1 overflow-hidden">
                    <h3 class="text-lg font-bold truncate">Dr. Sarah Budiarti</h3>
                    <p class="text-sm truncate opacity-90">Dokter Umum, Spesialis Anak</p>
                    <p class="text-xs mt-1 line-clamp-2 opacity-80">
                        Dokter umum berpengalaman dengan fokus pada kesehatan anak dan keluarga. Memberikan konsultasi yang ramah dan solutif.
                    </p>
                    <button class="mt-3 bg-white text-green-700 font-semibold px-4 py-2 rounded-full text-sm hover:bg-gray-100 transition duration-300 ease-in-out shadow-sm">
                        Lihat Detail
                    </button>
                </div>
            </div>
        </a>

        <a href="#" class="block">
            <div class="flex items-center p-4 rounded-xl text-white shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1"
                style="background: linear-gradient(to right, #FCD34D, #F97316, #EA580C);"> {{-- Example with another gradient --}}
                <img src="https://via.placeholder.com/80" alt="Dr. Wawan"
                    class="w-20 h-20 rounded-full object-cover border-2 border-white mr-4 flex-shrink-0" />
                <div class="flex-1 overflow-hidden">
                    <h3 class="text-lg font-bold truncate">Dr. Wawan Setiawan</h3>
                    <p class="text-sm truncate opacity-90">Spesialis Kulit & Kelamin</p>
                    <p class="text-xs mt-1 line-clamp-2 opacity-80">
                        Berpengalaman dalam berbagai kondisi kulit dan kelamin. Memberikan perawatan terbaik dengan pendekatan personal.
                    </p>
                    <button class="mt-3 bg-white text-orange-600 font-semibold px-4 py-2 rounded-full text-sm hover:bg-gray-100 transition duration-300 ease-in-out shadow-sm">
                        Lihat Detail
                    </button>
                </div>
            </div>
        </a>
        {{-- End Duplicate --}}

        {{-- @endforeach --}}
    </div>
</div>
@endsection

@section('script')
    {{-- Add any page-specific JavaScript here --}}
@endsection