@extends('layout')

@section('head')
    {{-- You can add specific CSS for this page here if needed --}}
@endsection

@section('content')
    <div class="max-w-4xl mx-auto p-4 min-h-screen font-sans antialiased pb-24 ">
        <div class="flex items-center justify-between mb-6 mt-4">
            <a href="{{ url()->previous() }}"
                class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out p-1 -ml-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex-grow text-center">
                Profil Dokter

            </h1>
            <div class="w-7"></div>
        </div>


        <div class="h-40"></div>
        <div class="relative w-full">
            <div class="relative z-20 -mt-32 md:-mt-32 flex justify-center">
                <img src="{{ asset($doctor->photo ?? 'appointment/doctor_placeholder.png') }}" alt="Dr. {{ $doctor->name }}"
                    class="w-48 h-48 md:w-56 md:h-56 object-cover rounded-full border-4 border-white shadow-xl ring-4 ring-blue-100">
            </div>
        </div>

        <div
            class="bg-gradient-to-r from-[#40ACD8] via-[#4980FF] to-[#2244C2] text-white w-[92%] sm:w-full max-w-xl mx-auto rounded-2xl p-6 md:p-8 shadow-xl -mt-16 md:-mt-20 pt-24 md:pt-28 text-center relative z-10">
            <h2 class="text-2xl md:text-3xl font-extrabold mb-1">{{ $doctor->front_title }} {{ $doctor->name }}
                {{ $doctor->back_title }}</h2>
            <p class="text-md md:text-xl font-semibold mb-3">{{ $doctor->specialization->name ?? 'Tidak Ada Spesialisasi' }}
            </p>
            <hr class="border-gray-300 my-4 opacity-50">
            <p class="text-sm md:text-base leading-relaxed mb-6 px-2">
                {{ $doctor->description ?? 'Tidak ada deskripsi untuk dokter ini.' }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-4">
                <a href="{{ route('user.booking.selectPatient', $doctor->id) }}"
                    class="w-full sm:w-auto bg-white text-blue-700 font-semibold px-6 py-3 rounded-full text-base md:px-8 md:py-4 md:text-lg hover:bg-gray-100 transition duration-300 ease-in-out shadow-lg transform hover:scale-105">
                    Buat Janji Temu
                </a>

                <div class="flex gap-3 mt-3 sm:mt-0">
                    <button
                        class="w-12 h-12 md:w-14 md:h-14 bg-white rounded-full flex items-center justify-center shadow-md border border-gray-200 hover:bg-gray-100 transition duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 22.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <button
                        class="w-12 h-12 md:w-14 md:h-14 bg-white rounded-full flex items-center justify-center shadow-md border border-gray-200 hover:bg-gray-100 transition duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.714-6 10-9 10s-9-5.286-9-10 6-10 9-10 9 5.286 9 10z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>


        {{-- Patient Review Section --}}
        <div
            class="w-[92%] sm:w-full max-w-xl mx-auto mt-6 bg-white p-6 md:p-8 rounded-2xl shadow-xl border border-gray-100">
            <h3 class="text-xl md:text-2xl font-semibold text-gray-700 mb-4">Ulasan Pasien</h3>
            <div class="flex items-center mb-4">
                <div class="text-3xl md:text-4xl font-bold text-blue-600 mr-2">4.9</div>
                <div class="flex text-yellow-400">
                    <svg class="w-5 h-5 md:w-6 md:h-6 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                    </svg>
                    <svg class="w-5 h-5 md:w-6 md:h-6 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                    </svg>
                    <svg class="w-5 h-5 md:w-6 md:h-6 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                    </svg>
                    <svg class="w-5 h-5 md:w-6 md:h-6 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                    </svg>
                    <svg class="w-5 h-5 md:w-6 md:h-6 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                    </svg>
                </div>
                <span class="text-gray-600 ml-2 md:text-base">(250 Ulasan)</span>
            </div>

            <div class="bg-gray-50 p-4 md:p-5 rounded-lg border border-gray-100">
                <p class="font-semibold text-gray-800 md:text-lg">Bagus sekali!</p>
                <p class="text-sm text-gray-600 mt-1 md:text-base">"Pelayanan Dr. {{ $doctor->name }} sangat ramah dan
                    profesional. Sangat merekomendasikan!"</p>
                <p class="text-xs text-gray-500 mt-2 text-right md:text-sm">- Pasien Anonim, {{ date('d F Y') }}</p>
            </div>
        </div>

    </div>
@endsection

@section('script')
@endsection
