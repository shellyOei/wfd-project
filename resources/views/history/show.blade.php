@extends('layout')

@section('title', 'Detail Riwayat Janji Temu')

@section('content')
<div class="max-w-md mx-auto bg-gray-50 min-h-screen">
    {{-- Header --}}
    <div class="flex items-center p-4 bg-white shadow-sm sticky top-0 z-10">
        <a href="{{ route('user.history.index') }}" class="text-gray-600">
            <i class="fa-solid fa-arrow-left fa-lg"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800 flex-grow text-center">Detail Janji Temu</h1>
        <div class="w-6"></div> {{-- Spacer --}}
    </div>

    <div class="p-4 space-y-4 pb-20">
        {{-- Status Card (Dinamis Sesuai Status) --}}
        @if($appointment->status == 3) {{-- 3 = Completed --}}
            <div class="bg-green-100 text-green-800 p-4 rounded-xl flex items-center shadow-sm">
                <i class="fa-solid fa-check-circle fa-lg mr-3"></i>
                <div>
                    <p class="font-bold">Selesai</p>
                    {{-- Menggunakan appointment_number yang lebih informatif --}}
                    <p class="text-xs">Reservasi #{{ $appointment->appointment_number }}</p>
                </div>
            </div>
        @elseif($appointment->status == 2) {{-- 2 = Canceled --}}
            <div class="bg-red-100 text-red-800 p-4 rounded-xl flex items-center shadow-sm">
                <i class="fa-solid fa-times-circle fa-lg mr-3"></i>
                <div>
                    <p class="font-bold">Dibatalkan</p>
                    <p class="text-xs">Reservasi #{{ $appointment->appointment_number }}</p>
                </div>
            </div>
        @endif

        {{-- Doctor Card --}}
        <div class="bg-white p-4 rounded-xl shadow-sm flex items-center">
            {{-- Menggunakan placeholder jika foto tidak ada --}}
            <img src="{{ asset($appointment->schedule->dayAvailable->doctor->photo) ?? asset('assets/doctor.png') }}" class="w-16 h-16 rounded-full mr-4 object-cover">
            <div>
                <p class="font-bold text-lg">dr. {{ $appointment->schedule->doctor->name }}</p>
                <p class="text-gray-600">{{ $appointment->schedule->doctor->specialization->name }}</p>
            </div>
        </div>

        {{-- Details --}}
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <h2 class="font-bold text-lg mb-4">Detail Pertemuan</h2>
            <div class="space-y-4">
                {{-- Detail Pasien --}}
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-user text-blue-500 w-6 text-center"></i>
                    <span class="text-gray-800 font-medium">{{ $appointment->patient->name }}</span>
                </div>

                {{-- Detail Jadwal --}}
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-calendar-alt text-blue-500 w-6 text-center"></i>
                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($appointment->schedule->Datetime)->translatedFormat('l, d F Y, H:i') }} WIB</span>
                </div>

                {{-- Detail Keluhan --}}
                <div class="flex items-start space-x-2">
                    <i class="fa-solid fa-comment-dots text-blue-500 w-6 text-center mt-1"></i>
                    <div>
                        <p class="text-gray-500 text-sm">Keluhan</p>
                        <p class="text-gray-800 font-medium">{{ $appointment->subjective ?? 'Tidak ada keluhan tercatat.' }}</p>
                    </div>
                </div>
            </div>
        </div>

      

        {{-- Tombol Download hanya muncul jika appointment Selesai --}}
        @if($appointment->status == 3)
        <div class="pt-4">
             {{-- Ganti href dengan route/URL download yang sebenarnya --}}
            <a href="#" class="block text-center w-full py-3 rounded-xl text-white font-semibold shadow-lg bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 transition">
                <i class="fa-solid fa-download mr-2"></i>
                Unduh Bukti Reservasi
            </a>
        </div>
        @endif
    </div>
</div>
@endsection