@extends('layout')

@section('title', 'Detail Riwayat Janji Temu')

@section('content')
{{-- The outer container now handles the background color and screen height --}}
<div class="bg-gray-50 min-h-screen">

    {{-- =================================================================== --}}
    {{-- MOBILE VIEW HEADER (Identical to your original code, but hides on desktop) --}}
    {{-- =================================================================== --}}
    <div class="flex items-center p-4 bg-white shadow-sm sticky top-0 z-10 md:hidden">
        <a href="{{ route('user.history.index') }}" class="text-gray-600">
            <i class="fa-solid fa-arrow-left fa-lg"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800 flex-grow text-center">Detail Janji Temu</h1>
        <div class="w-6"></div> {{-- Spacer --}}
    </div>

    {{-- Main content wrapper for both mobile and desktop --}}
    {{-- Provides padding and sets the max-width for desktop --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- =================================================================== --}}
        {{-- DESKTOP VIEW HEADER (Hidden on mobile) --}}
        {{-- =================================================================== --}}
        <div class="hidden md:flex items-center mb-6">
            <a href="{{ route('user.history.index') }}" class="text-gray-500 hover:text-gray-800 transition">
                <i class="fa-solid fa-arrow-left fa-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900 ml-4">Detail Riwayat Janji Temu</h1>
        </div>

        {{-- =================================================================== --}}
        {{-- RESPONSIVE GRID LAYOUT --}}
        {{-- On mobile: Stacks vertically (default div behavior) --}}
        {{-- On desktop (lg): Becomes a 2-column grid --}}
        {{-- =================================================================== --}}
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">

            {{-- MAIN CONTENT (Left Column on Desktop) --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Doctor Card (No changes needed, works on all sizes) --}}
                <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm flex items-center">
                    <img src="{{ asset($appointment->schedule->dayAvailable->doctor->photo) ?? asset('assets/doctor.png') }}" class="w-20 h-20 rounded-full mr-6 object-cover">
                    <div>
                        <p class="font-bold text-xl text-gray-900">dr. {{ $appointment->schedule->doctor->name }}</p>
                        <p class="text-gray-600 text-md">{{ $appointment->schedule->doctor->specialization->name }}</p>
                    </div>
                </div>

                {{-- Details (No changes needed, works on all sizes) --}}
                <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm">
                    <h2 class="font-bold text-xl mb-6">Detail Pertemuan</h2>
                    <div class="space-y-5">
                        {{-- Detail Pasien --}}
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-user text-blue-500 w-6 text-center fa-lg"></i>
                            <span class="text-gray-800 font-medium text-md">{{ $appointment->patient->name }}</span>
                        </div>

                        {{-- Detail Jadwal --}}
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-calendar-alt text-blue-500 w-6 text-center fa-lg"></i>
                            {{-- Using translatedFormat for locale-aware date formatting --}}
                            <span class="text-gray-700 text-md">{{ \Carbon\Carbon::parse($appointment->schedule->Datetime)->translatedFormat('l, d F Y, H:i') }} WIB</span>
                        </div>

                        {{-- Detail Keluhan --}}
                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-comment-dots text-blue-500 w-6 text-center mt-1 fa-lg"></i>
                            <div>
                                <p class="text-gray-500 text-sm">Keluhan</p>
                                <p class="text-gray-800 font-medium text-md">{{ $appointment->subjective ?? 'Tidak ada keluhan tercatat.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR (Right Column on Desktop) --}}
            {{-- This column is sticky on desktop so it stays in view --}}
            <div class="space-y-6 lg:sticky lg:top-24">
                 {{-- Status Card (Dinamis Sesuai Status) --}}
                 {{-- Added a margin-top for mobile view separation, which is ignored on desktop due to space-y --}}
                @if($appointment->status == 3) {{-- 3 = Completed --}}
                    <div class="bg-green-100 text-green-800 p-4 rounded-xl flex items-center shadow-sm mt-6 lg:mt-0">
                        <i class="fa-solid fa-check-circle fa-2x mr-4"></i>
                        <div>
                            <p class="font-bold text-lg">Selesai</p>
                            <p class="text-sm">Reservasi #{{ $appointment->appointment_number }}</p>
                        </div>
                    </div>
                @elseif($appointment->status == 2) {{-- 2 = Canceled --}}
                    <div class="bg-red-100 text-red-800 p-4 rounded-xl flex items-center shadow-sm mt-6 lg:mt-0">
                        <i class="fa-solid fa-times-circle fa-2x mr-4"></i>
                        <div>
                            <p class="font-bold text-lg">Dibatalkan</p>
                            <p class="text-sm">Reservasi #{{ $appointment->appointment_number }}</p>
                        </div>
                    </div>
                @endif

                {{-- Tombol Download hanya muncul jika appointment Selesai --}}
                @if($appointment->status == 3)
                    <div class="pt-2"> {{-- Reduced top padding --}}
                        {{-- Ganti href dengan route/URL download yang sebenarnya --}}
                        <a href="#" class="block text-center w-full py-3 rounded-xl text-white font-semibold shadow-lg bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 transition transform hover:-translate-y-1">
                            <i class="fa-solid fa-download mr-2"></i>
                            Unduh Bukti Reservasi
                        </a>
                    </div>
                @endif
            </div>

        </div>
        {{-- Add padding-bottom for mobile to avoid overlap with sticky bottom nav --}}
        <div class="pb-20 lg:pb-0"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // This script likely targets a main layout menu, no changes needed here.
        highlightActiveMenu('appointment');
    });
</script>
@endpush