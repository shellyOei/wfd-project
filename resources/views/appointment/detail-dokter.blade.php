@extends('layout')

@section('head')
    {{-- Anda bisa menambahkan meta tag atau link CSS spesifik di sini jika diperlukan untuk halaman ini --}}
@endsection

@section('content')
<div class="max-w-md mx-auto p-0 bg-gray-50 min-h-screen flex flex-col items-center pb-8 font-sans antialiased">

    <div class="w-full flex items-center justify-between p-4 bg-white shadow-sm z-20">
        <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800 flex-1 text-center">Dokter</h1>
        <div class="w-7"></div> {{-- Spacer untuk simetri --}}
    </div>

    <div class="relative w-full h-48  flex items-center justify-center -mt-1 shadow-inner">
        {{-- Div ini membuat latar belakang berwarna di belakang gambar --}}
    </div>

    <div class="relative z-10 -mt-24 mb-6"> {{-- Margin negatif disesuaikan untuk tumpang tindih yang lebih baik --}}
        <img src="{{ asset('appointment/doctor.png') }}" alt="Dr. Andrew S.pd" class="w-36 h-36 object-cover rounded-full border-4 border-white shadow-xl">
    </div>

    <div class="bg-gradient-to-r from-[#40ACD8] via-[#4980FF] to-[#2244C2] text-white w-[92%] rounded-2xl p-6 shadow-xl -mt-16 pt-24 text-center">
        <h2 class="text-2xl font-extrabold mb-1">Dr. Andrew S.pd</h2>
        <p class="text-md font-semibold mb-3">Spesialis Jantung, Spesialis Ortho</p>
        <hr class="border-gray-300 my-4 opacity-50"> {{-- Garis pemisah disesuaikan agar cocok dengan latar belakang gelap --}}
        <p class="text-sm leading-relaxed mb-6 px-2">
            Saya adalah seorang dokter gigi berpengalaman dengan lebih dari 10 tahun praktik. Saya mengkhususkan diri dalam kedokteran gigi umum dan menawarkan berbagai layanan untuk kesehatan gigi Anda.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-4">
            <a href="#" class="w-full sm:w-auto bg-white text-blue-700 font-semibold px-6 py-3 rounded-full text-base hover:bg-gray-100 transition duration-300 ease-in-out shadow-lg transform hover:scale-105">
                Buat Janji Temu
            </a>
            <div class="flex gap-3 mt-3 sm:mt-0">
                <button class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md border border-gray-200 hover:bg-gray-100 transition duration-300 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 22.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
                <button class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md border border-gray-200 hover:bg-gray-100 transition duration-300 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.714-6 10-9 10s-9-5.286-9-10 6-10 9-10 9 5.286 9 10z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Opsional: Tambahkan bagian di bawah kartu utama untuk detail lebih lanjut --}}
    <div class="w-[92%] mt-6 bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Lokasi & Jadwal</h3>
        <p class="text-gray-600 mb-2">
            <strong class="text-gray-800">Klinik Sehat Selalu</strong><br>
            Jl. Raya Kesehatan No. 123, Surabaya, Jawa Timur
        </p>
        <p class="text-gray-600 mb-2">
            <strong class="text-gray-800">Senin - Jumat:</strong> 09:00 - 17:00 WIB
        </p>
        <p class="text-gray-600">
            <strong class="text-gray-800">Sabtu:</strong> 09:00 - 13:00 WIB
        </p>
        {{-- Anda mungkin ingin menambahkan komponen peta di sini --}}
    </div>

    <div class="w-[92%] mt-6 bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Ulasan Pasien</h3>
        <div class="flex items-center mb-4">
            <div class="text-3xl font-bold text-blue-600 mr-2">4.9</div>
            <div class="flex text-yellow-400">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
            </div>
            <span class="text-gray-600 ml-2">(250 Ulasan)</span>
        </div>
        {{-- Contoh ulasan --}}
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
            <p class="font-semibold text-gray-800">Bagus sekali!</p>
            <p class="text-sm text-gray-600 mt-1">"Pelayanan Dr. Andrew sangat ramah dan profesional. Sangat merekomendasikan!"</p>
            <p class="text-xs text-gray-500 mt-2 text-right">- Pasien A.K., 5 Juni 2025</p>
        </div>
    </div>

</div>
@endsection

@section('script')
    {{-- Anda bisa menambahkan JavaScript khusus halaman di sini jika diperlukan --}}
@endsection