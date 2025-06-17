@extends('layout')

@section('content')
<div class="max-w-md mx-auto p-5 bg-gray-50 min-h-screen pb-24">
    <div class="flex justify-evenly mb-10 mt-4">
        <a href="{{ url()->previous() }}" class="mr-2 text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-xl font-bold flex-grow text-center text-gray-800">Pilih Pasien</h1>
        <a id="openModalBtn" href="javascript:void(0)" class="text-blue-600 hover:text-blue-800 transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20v-1a4 4 0 014-4h4a4 4 0 014 4v1"/>
            </svg>
        </a>
    </div>

    @if ($patients->isNotEmpty())
    <div class="flex flex-col w-[95%] mx-auto">
        <form class="space-y-6" id="selectPatientForm" method="GET" action="#">
            <input type="hidden" name="patient_id" id="selectedPatientId">

            @foreach ($patients as $patient)
                <div tabindex="0"
                    class="patient-card w-full bg-white space-y-2 shadow-md p-4 rounded-xl p-5 flex items-center space-x-4
                            cursor-pointer transition-all duration-200 ease-in-out
                            hover:shadow-lg hover:scale-[1.01]
                            focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-[var(--blue1)]"
                    data-patient-id="{{ $patient->id }}">

                    <img class="w-1/4" src="{{ asset('assets/profile-avatar.jpg')}}" alt="Patient Profile Picture">
                    <p class="text-black font-bold text-xl">{{ $patient->name }}</p>
                </div>
            @endforeach

            <div class="">
                <button id="selectPatientBtn" type="button" class="text-center w-full py-3 rounded-lg text-white font-semibold shadow-lg
                    bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)]
                    hover:from-purple-600 hover:to-indigo-700
                    hover:shadow-xl transition-all duration-300 ease-in-out
                    focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-opacity-75 font-bold tracking-wide">
                    Pilih Pasien
                </button>
            </div>
        </form>
    </div>
    @else
        <div class="text-center mt-10">
            <p class="text-gray-600">Anda belum memiliki pasien terdaftar.</p>
            <a href="{{ route('user.register.patient') }}" class="mt-4 inline-block px-6 py-2 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Daftarkan Pasien Baru
            </a>
        </div>
    @endif
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const patientCards = document.querySelectorAll('.patient-card');
        const selectedPatientIdInput = document.getElementById('selectedPatientId');
        const selectPatientBtn = document.getElementById('selectPatientBtn');

        // Pass the doctor ID from Blade to JavaScript
        const doctorId = "{{ $doctor->id }}";
        const bookingShowRoute = "{{ route('user.booking.show', ['doctor' => ':doctor_id', 'patient' => ':patient_id']) }}";

        selectPatientBtn.disabled = true;

        patientCards.forEach(card => {
            card.addEventListener('click', function () {
                patientCards.forEach(c => c.classList.remove('border-[var(--blue1)]', 'ring-2', 'ring-[var(--blue1)]'));

                this.classList.add('border-blue-600', 'ring-2', 'ring-blue-600');

                const patientId = this.dataset.patientId;
                selectedPatientIdInput.value = patientId;
                console.log('Selected Patient ID:', patientId);
                selectPatientBtn.disabled = false;
            });

            card.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.keyCode === 13) {
                    event.preventDefault();
                    this.click();
                }
            });
        });

        selectPatientBtn.addEventListener('click', function() {
            const selectedPatientId = selectedPatientIdInput.value;

            if (selectedPatientId) {
                let finalUrl = bookingShowRoute.replace(':doctor_id', doctorId);
                finalUrl = finalUrl.replace(':patient_id', selectedPatientId);

                console.log('Redirecting to:', finalUrl);

                window.location.href = finalUrl;
                
            }
        });

        const initialSelectedId = selectedPatientIdInput.value;
        if (initialSelectedId) {
             patientCards.forEach(card => {
                if (card.dataset.patientId === initialSelectedId) {
                    card.classList.add('border-blue-600', 'ring-2', 'ring-blue-600');
                    selectPatientBtn.disabled = false;
                }
            });
        }
    });
</script>
@endsection