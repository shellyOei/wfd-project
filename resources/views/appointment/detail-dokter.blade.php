@extends('layout')

@section('head')
    
@endsection

@section('content')
{{-- <div class="min-h-screen bg-gray-50 flex flex-col items-center pt-4">
     --}}
       <div class="max-w-md mx-auto p-4">

    <!-- Header -->
    <div class="w-full flex items-center justify-between px-4">
        <a href="{{ url()->previous() }}" class="text-xl">&#8592;</a>
        <h1 class="text-xl font-semibold text-center flex-1">Dokter</h1>
        <span class="w-6"></span>
    </div>

    <!-- Dokter Image -->
    <div class="mt-6 relative z-10">
        <img src="{{ asset('images/doctor-andrew.png') }}" alt="Dr. Andrew" class="w-40 h-40 object-cover rounded-full border-4 border-white shadow-lg">
    </div>

    <!-- Detail Card -->
    <div class="bg-gradient-to-r from-[#40ACD8] via-[#4980FF] to-[#2244C2] text-white w-[90%] mt-[-40px] rounded-3xl p-6 pt-16 relative z-0 shadow-lg">
        <h2 class="text-lg font-bold">Dr. Andrew S.pd</h2>
        <p class="text-sm mb-2">Spesialis jantung, Spesialis Ortho</p>
        <p class="text-sm leading-relaxed">
            I am an experienced dentist with over 10 years of practice.
            I am specialized in general dentistry and I will offer a range of services.
        </p>

        <!-- Button + Like -->
        <div class="flex items-center mt-4 gap-3">
            <button class="bg-white text-[#4980FF] font-semibold px-4 py-2 rounded-full text-sm hover:bg-gray-100">
                Buat Janji Temu
            </button>
            <button class="w-9 h-9 bg-white rounded-full flex items-center justify-center hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-[#4980FF] w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>
    </div>

</div>
@endsection

@section('script')

@endsection
