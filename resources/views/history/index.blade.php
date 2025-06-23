@extends('layout') {{-- Sesuaikan dengan nama layout utama Anda --}}

@section('title', 'Riwayat Janji Temu')

@section('content')
<div class="min-h-screen px-4">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6 mt-4">
        <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-800 p-1 -ml-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-xl md:text-2xl font-bold text-gray-800 flex-grow text-center">
            Riwayat Janji Temu
        </h1>
        <div class="w-7"></div> {{-- Spacer untuk menyeimbangkan header --}}
    </div>

    {{-- Filter Status Buttons --}}
    <div class="flex items-center gap-x-3 mb-4">
        {{-- Tombol "Semua" tidak mengirim parameter status --}}
        <a href="{{ route('user.history.index', ['patient_id' => request('patient_id')]) }}" class="flex-1 text-center px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-700' }}">Semua</a>
        
        {{-- PERBAIKAN: Kirim status=3 untuk Selesai dan cek dengan angka 3 --}}
        <a href="{{ route('user.history.index', ['status' => 3, 'patient_id' => request('patient_id')]) }}" class="flex-1 text-center px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 3 ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-700' }}">Selesai</a>
        
        {{-- PERBAIKAN: Kirim status=2 untuk Dibatalkan dan cek dengan angka 2 --}}
        <a href="{{ route('user.history.index', ['status' => 2, 'patient_id' => request('patient_id')]) }}" class="flex-1 text-center px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 2 ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-700' }}">Dibatalkan</a>
    </div>
    
    {{-- Filter Pasien (Dropdown) --}}
    @if(isset($userPatients) && $userPatients->count() > 1)
    <div class="mb-4">
        <form action="{{ route('user.history.index') }}" method="GET" id="patientFilterForm">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <select name="patient_id" onchange="this.form.submit()" class="w-full text-sm bg-white border-none rounded-lg py-3 px-3 text-gray-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                <option value="">Tampilkan Semua Pasien</option>
                @foreach ($userPatients as $patient)
                    <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                        {{ $patient->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    @endif

    {{-- Appointment List --}}
    <div class="space-y-3 pb-20">
        @forelse ($appointments as $app)
        <a href="{{ route('user.history.show', $app->id) }}" class="block bg-white p-4 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div class="flex-grow">
                    @if(!request('patient_id') && isset($userPatients) && $userPatients->count() > 1)
                        <p class="text-xs font-bold text-blue-600 mb-1 flex items-center">
                            <i class="fa-solid fa-user mr-2"></i>
                            {{ $app->patient->name }}
                        </p>
                    @endif
                    <p class="text-sm font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($app->schedule->Datetime)->translatedFormat('l, d F Y') }}
                    </p>
                    <p class="text-xs text-gray-500 mb-2">
                        {{ \Carbon\Carbon::parse($app->schedule->Datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($app->schedule->Datetime)->addMinutes(30)->format('H:i') }}
                    </p>
                    <p class="font-bold text-gray-900">dr. {{ $app->schedule->doctor->name }}</p>
                    <p class="text-xs text-gray-600">{{ $app->schedule->doctor->specialization->name }}</p>
                </div>
                <div class="flex flex-col items-end justify-between h-full min-h-[70px]">
                    {{-- Bagian ini sudah benar menggunakan angka 2 dan 3 --}}
                    @if($app->status == 3)
                        <span class="text-xs font-semibold text-green-800 bg-green-100 px-3 py-1 rounded-full">Selesai</span>
                    @elseif($app->status == 2)
                        <span class="text-xs font-semibold text-red-800 bg-red-100 px-3 py-1 rounded-full">Dibatalkan</span>
                    @endif
                    <i class="fa-solid fa-chevron-right text-gray-400 mt-auto"></i>
                </div>
            </div>
        </a>
        @empty
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak Ada Riwayat</h3>
                <p class="mt-1 text-sm text-gray-500">Tidak ada riwayat janji temu yang sesuai dengan filter ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        highlightActiveMenu('appointment');
    });
</script>
@endpush