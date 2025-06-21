@extends('layout')

@section('content')
<div class="max-w-md mx-auto bg-gray-50 min-h-screen">
    {{-- Header --}}
    <div class="flex items-center p-4 bg-white shadow-sm">
        <a href="{{ route('user.history.index') }}" class="text-gray-600">
            <i class="fa-solid fa-arrow-left fa-lg"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800 flex-grow text-center">Record #{{ substr($appointment->id, 0, 8) }}</h1>
        <div class="w-6"></div> {{-- Spacer --}}
    </div>

    <div class="p-4 space-y-4">
        {{-- Status Card --}}
        <div class="bg-green-100 text-green-800 p-4 rounded-xl flex items-center">
            <i class="fa-solid fa-check-circle fa-lg mr-3"></i>
            <div>
                <p class="font-bold">Selesai</p>
                <p class="text-xs">Reservation #WWDT{{ substr(str_replace('-', '', $appointment->id), 0, 6) }}</p>
            </div>
        </div>

        {{-- Doctor Card --}}
        <div class="bg-white p-4 rounded-xl shadow-sm flex items-center">
            <img src="{{ asset($appointment->schedule->doctor->photo ?? 'doctors/doctor.png') }}" class="w-16 h-16 rounded-full mr-4">
            <div>
                <p class="font-bold text-lg">dr. {{ $appointment->schedule->doctor->name }}</p>
                <p class="text-gray-600">{{ $appointment->schedule->doctor->specialization->name }}</p>
            </div>
        </div>

        {{-- Details --}}
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <h2 class="font-bold text-lg mb-2">Detail Pertemuan</h2>
            <div class="flex items-center mb-2">
                <i class="fa-solid fa-calendar-alt text-blue-500 w-6"></i>
                <span class="text-gray-700">{{ \Carbon\Carbon::parse($appointment->schedule->Datetime)->translatedFormat('l, d F Y, H:i') }}</span>
            </div>
            <div class="flex items-start">
                <i class="fa-solid fa-comment-dots text-blue-500 w-6 mt-1"></i>
                <div>
                    <p class="text-gray-500">Keluhan</p>
                    <p class="text-gray-800 font-medium">{{ $appointment->subjective ?? 'Tidak ada keluhan utama.' }}</p>
                </div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <h2 class="font-bold text-lg mb-2">Dokumen Terlampir</h2>
            <div class="space-y-2">
                @forelse($appointment->documents as $doc)
                <a href="#" class="flex items-center p-3 bg-gray-100 rounded-lg hover:bg-gray-200">
                    <i class="fa-solid fa-file-pdf text-red-500 fa-lg mr-3"></i>
                    <span class="text-gray-800">{{ $doc->file_name }}</span>
                </a>
                @empty
                <p class="text-sm text-gray-500">Tidak ada dokumen terlampir.</p>
                @endforelse
            </div>
        </div>

        {{-- Download Button --}}
        <div class="pt-4">
            <a href="#" class="block text-center w-full py-3 rounded-xl text-white font-semibold shadow-lg bg-gradient-to-r from-cyan-400 to-blue-600">
                <i class="fa-solid fa-download mr-2"></i>
                Unduh Bukti Reservasi
            </a>
        </div>
    </div>
</div>
@endsection