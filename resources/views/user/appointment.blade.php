@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 min-h-screen antialiased pb-24">
    <div class="flex items-center justify-between mb-6">
        <button onclick="history.back()" class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out p-1 -ml-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex-grow text-center">Janji Temu</h1>
        <div class="w-7"></div> 
    </div>

    {{-- Patient Selection Dropdown --}}
    @if (!empty($linkedPatients))
        <div class="mb-6 px-4">
            <label for="patient_select" class="block text-gray-700 text-sm font-bold mb-2">Lihat Janji Temu Untuk Pasien:</label>
            <select id="patient_select" name="patient_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @foreach ($linkedPatients as $patient)
                    <option value="{{ $patient->id }}" {{ $patient->id == $selectedPatientId ? 'selected' : '' }}>
                        {{ $patient->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @else
        <p class="text-gray-600 mb-6">Tidak ada pasien yang terdaftar pada akun ini.</p>
    @endif



    {{-- CORRECTED SECTION: Iterate through $groupedAppointments --}}
    <div class="flex flex-col max-sm:px-5 gap-4 sm:gap-4">
        @forelse ($groupedAppointments as $dateString => $appointmentsOnThisDate)
            <div class="mb-8">
                <h3 class="text-lg font-bold text-[var(--blue2)] mb-4">{{ \Carbon\Carbon::parse($dateString)->isoFormat('dddd, D MMMM YYYY') }}</h3>

                @foreach ($appointmentsOnThisDate as $appointment)
                    @php
                        $doctor = $appointment->schedule->dayAvailable->doctor ?? null;
                        $appointmentTime = $appointment->schedule->Datetime->format('H.i');
                    @endphp

                    <div class="flex items-center mb-4">
                        <div class="text-lg font-semibold text-black w-20 pt-1">{{ $appointmentTime }}</div>
                        <div class="flex-grow bg-white rounded-xl shadow-md p-4 flex items-center space-x-4 ml-4
                            bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)]">

                            <div class="relative w-16 h-16 rounded-full overflow-hidden flex items-center justify-center bg-white text-gray-700 text-2xl font-bold border-2 border-white">
                                <img src="{{asset($doctor->photo)}}" alt="{{ $doctor->name }}" class="object-cover w-full h-full">
                            </div>

                            {{-- Doctor Details and Buttons --}}
                            <div class=" flex flex-col items-center justify-between">
                                <div class="text-white">
                                    <h4 class="font-bold text-lg">dr. {{ $doctor->name ?? 'N/A' }}
                                    <p class="">{{ $doctor->specialization->name ?? 'Umum' }}
                                </div>
                                <div class="flex space-x-2">
                                    <button class="bg-blue-200 text-blue-800 font-medium py-2 px-4 rounded-full hover:bg-blue-300 transition duration-150">Lihat</button>
                                    <button class="bg-blue-800 text-white font-medium py-2 px-4 rounded-full hover:bg-blue-900 transition duration-150">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="text-center py-10">
                <p class="text-gray-600 text-lg">Tidak ada janji temu yang ditemukan untuk pasien yang dipilih.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection