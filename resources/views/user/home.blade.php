@extends('layout')

@section('content')
<!-- Selamat Datang -->
@auth
    {{-- Content for authenticated users --}}
    <div class="text-black flex justify-between items-center mb-6 w-full bg-white p-4 shadow-md">
        <div class="flex items-center space-x-3">
            <div class="w-20 h-20 rounded-full mx-auto flex items-center justify-center">
                <img src="/assets/ewaps-logo.png" alt="Logo" class="w-full h-full mx-auto rounded-full ">
            </div>
            <div>
                <p class="text-gray-500 text-sm">Selamat Datang,</p>
                <p class="font-bold text-xl">{{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>
@else
    {{-- Content for unauthenticated users --}}
    <div class="text-black flex justify-between items-center mb-6 w-full bg-white p-4 shadow-md">
        <div class="flex items-center space-x-3">
            <div class="w-20 h-20 rounded-full mx-auto flex items-center justify-center">
                <img src="/assets/ewaps-logo.png" alt="Logo" class="w-full h-full mx-auto rounded-full ">
            </div>
            <div>
                <p class="text-gray-500 text-sm">Selamat Datang,</p>
                <p class="font-bold text-xl">Tamu</p> {{-- Or "Silakan Login" or similar --}}
            </div>
        </div>
    </div>
@endauth
<div class="max-w-7xl mx-auto p-4 pb-20">
        <!-- Layanan -->
        <div class="relative bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)] rounded-3xl p-6 flex flex-col md:flex-row items-center text-white mb-8">
            <div class="md:w-1/2 md:mb-0">
                <h2 class="text-xl font-semibold">Layanan</h2>
                <h2 class="text-xl font-semibold">Pemeriksaan Laboratorium</h2>
                <p class="text-sm mt-2 text-[var(--gray1)]">Dapatkan hasil lab yang akurat dan terpercaya kapan saja, di mana saja</p>
                <button class="mt-4 bg-white text-blue-600 font-semibold px-4 py-2 rounded-3xl shadow-xl">Book Now</button>
            </div>
            <img class="absolute opacity-50 bottom-0 right-0 h-full" src="{{ asset('assets/doctor.png')}}" alt="">
            {{-- <div class="md:w-1/2 flex justify-center">
                <div class="w-40 h-40 md:w-60 md:h-60 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-flask fa-3x"></i>
                </div>
            </div> --}}
        </div>

        <!-- Notifikasi -->
        <div class="mb-8">
            <h3 class="font-bold text-lg mb-3">Notifikasi Penting <i class="fa-solid fa-bell text-yellow-400"></i></h3>
            <div class="space-y-2">
                <div class="border border-blue-500 rounded p-3">
                    <span class="text-sm">Hasil Lab Tersedia - Hasil pemeriksaan darah Anda sudah siap</span>
                </div>
                <div class="border border-blue-500 rounded p-3">
                    <span class="text-sm">Pengingat Konsultasi - Besok, 09:00 dengan Dr. Andrew</span>
                </div>
            </div>
        </div>

        <!-- Dokter Rekomendasi -->
        <div class="mb-8">
            <h3 class="font-bold text-lg mb-3">Dokter Rekomendasi</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow p-3 text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full mx-auto flex items-center justify-center">
                        <img src="/doctors/Andrew.png" alt="Profil Dr. Andrew" class="w-full h-full mx-auto rounded-full object-cover object-top">
                    </div>
                    <p class="font-semibold mt-2">dr. Andrew</p>
                    <p class="text-sm text-gray-500">Dokter Umum</p>
                    <div class="text-yellow-400 mt-1">⭐ 4.9</div>
                </div>
                <div class="bg-white rounded-lg shadow p-3 text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full mx-auto flex overflow-hidden items-center justify-center">
                        <img src="/doctors/Amba.png" alt="Profil Dr. Amba" class="w-full h-full mx-auto rounded-full object-cover object-top scale-125">
                    </div>
                    <p class="font-semibold mt-2">dr. Amba</p>
                    <p class="text-sm text-gray-500">Kardiologi</p>
                    <div class="text-yellow-400 mt-1">⭐ 4.9</div>
                </div>
                <div class="bg-white rounded-lg shadow p-3 text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full mx-auto flex items-center justify-center">
                        <img src="/doctors/Jessi.png" alt="Profil Dr. Jessi" class="w-full h-full mx-auto rounded-full object-top object-cover">
                    </div>
                    <p class="font-semibold mt-2">dr. Jessi</p>
                    <p class="text-sm text-gray-500">Dermatologi</p>
                    <div class="text-yellow-400 mt-1">⭐ 4.9</div>
                </div>
            </div>
        </div>

        <!-- Artikel -->
        <div>
            <h3 class="font-bold text-lg mb-3">Artikel Terkait</h3>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="w-full h-55 flex items-center justify-center">
                   <img src="/Artikel/Artikel.png" alt="Artikel 1" class="w-full h-full mx-auto object-cover object-top">
                </div>
                <div class="p-4">
                    <p class="font-semibold">Ditemukan Covid-25</p>
                    <p class="text-sm text-gray-500">Virus varian wabah baru saja ditemukan di daerah Indonesia...</p>
                    <a href="#" class="text-blue-600 text-sm mt-2 inline-block">Baca Selengkapnya</a>
                </div>
            </div>
        </div>
    </div>
@endsection 

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
       highlightActiveMenu('home');
    });
</script>
@endpush