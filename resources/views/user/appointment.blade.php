@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 min-h-screen antialiased pb-24">
    <div class="flex items-center justify-between mb-6">
        <button onclick="history.back()" class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out p-1 -ml-1 flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 text-center">Janji Temu</h1>
        <a href="{{ route('user.history.index')}}"class=" text-[var(--blue1)] font-medium py-1 px-2 rounded-md hover:text-blue-300 transition duration-150 cursor-pointer flex items-center space-x-1 flex-1 justify-end">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Riwayat</span>
        </a>
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
    <div class="flex flex-col max-sm:px-3 gap-6 sm:gap-4">
        @forelse ($groupedAppointments as $dateString => $appointmentsOnThisDate)
            <div class="mb-8">
                <h3 class="text-lg font-bold text-[var(--blue2)] mb-4">{{ \Carbon\Carbon::parse($dateString)->isoFormat('dddd, D MMMM YYYY') }}</h3>

                @foreach ($appointmentsOnThisDate as $appointment)
                    @php
                        $doctor = $appointment->schedule->dayAvailable->doctor ?? null;
                        $appointmentTime = $appointment->schedule->Datetime->format('H.i');
                    @endphp

                    <div class="flex items-center mb-4 items-center ">
                        <div class="text-lg font-semibold text-black w-20 pt-1">{{ $appointmentTime }}</div>
                        <div class="w-full bg-white rounded-xl shadow-md p-4 flex items-center space-x-3 ml-4
                            bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)]">

                            <div class="relative w-16 h-16 rounded-full overflow-hidden flex items-center justify-center bg-white text-gray-700 text-2xl font-bold border-2 border-white">
                                <img src="{{asset($doctor->photo)}}" alt="{{ $doctor->name }}" class="object-cover w-full h-full">
                            </div>

                            <div class=" flex flex-col items-start justify-between space-y-2">
                                <div class="text-white">
                                    <h4 class="font-medium text-md">{{$doctor->front_title}} {{ $doctor->name ?? 'N/A' }}
                                    <p class="font-normal">{{ $doctor->specialization->name ?? 'Umum' }}
                                </div>
                                <div class="flex space-x-2">
                                    {{-- notes --}}
                                    <button
                                        type="button"
                                        class="bg-white text-[var(--blue1)] font-medium py-1 px-6 rounded-md hover:bg-blue-300 transition duration-150 cursor-pointer"
                                        onclick="openAppointmentDetailsModal(
                                            '{{ $doctor->front_title ?? '' }} {{ $doctor->name ?? 'N/A' }}',
                                            '{{ $doctor->specialization->name ?? 'Umum' }}',
                                            '{{ \Carbon\Carbon::parse($appointment->schedule->Datetime)->isoFormat('dddd, D MMMM YYYY') }}',
                                            '{{ $appointment->patient->name ?? 'N/A' }}',
                                            '{{ $appointmentTime }}',
                                            '{{ $appointment->queue_number ?? 'N/A' }}' ,
                                            '{{ $appointment->id }}',
                                            '{{ $appointment->notes ?? 'Tidak ada' }}'
                                        )"
                                    >
                                        Lihat
                                    </button>
                                    
                                    <button
                                        type="button"
                                        class="bg-transparent text-white font-medium py-1 border border-white px-6 rounded-md hover:bg-blue-900 transition duration-150 cursor-pointer"
                                        data-appointment-id="{{ $appointment->id }}"
                                    >
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- The Modal Structure --}}
                <div id="appointmentModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto relative">
                        <button id="closeModalButton" class="absolute top-3 right-3 text-red-500 hover:text-red-700 transition duration-150 p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-800" id="modalDoctorName"></h2>
                        </div>
                        <p class="text-gray-600 mb-4" id="modalDoctorSpecialty"></p>
                        <div class="border-b border-gray-300 mb-4"></div>

                        <p class="text-lg font-bold text-gray-800 mb-4" id="modalAppointmentDate"></p>

                        <div class="space-y-2 text-gray-700 mb-6">
                            <div class="flex justify-between">
                                <span class="font-semibold">NAMA LENGKAP</span>
                                <span id="modalPatientName"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">JAM KONSULTASI</span>
                                <span id="modalConsultationTime"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">NOMOR ANTRIAN</span>
                                <span id="modalQueueNumber"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">CATATAN</span>
                                <span id="modalAppointmentNote">Tidak ada</span> 
                            </div>
                        </div>

                        <button id="addNoteButton" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-150">Tambahkan Catatan</button>
                    </div>
                </div>

                <div id="noteModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto relative">
                        <button id="closeNoteModalButton" class="absolute top-3 right-3 text-red-500 hover:text-red-700 transition duration-150 p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <h2 class="text-xl font-bold text-gray-800 mb-4">Tambah/Edit Catatan</h2>

                        <form id="noteForm">
                            @csrf 
                            <input type="hidden" id="noteAppointmentId" name="appointment_id">
                            <div class="mb-4">
                                <label for="appointmentNote" class="block text-gray-700 text-sm font-bold mb-2">Catatan:</label>
                                <textarea id="appointmentNote" name="note" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan catatan di sini..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-150">Simpan Catatan</button>
                        </form>
                    </div>
                </div>
        @empty
            <div class="text-center py-10">
                <p class="text-gray-600 text-lg">Tidak ada janji temu yang ditemukan untuk pasien yang dipilih.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        highlightActiveMenu('appointment');

        const patientSelect = document.getElementById('patient_select');
        if (patientSelect) {
            patientSelect.addEventListener('change', function() {
                const selectedPatientId = this.value;
                window.location.href = "{{ route('user.appointments.index') }}?patient_id=" + selectedPatientId;
            });
        }

        const cancelButtons = document.querySelectorAll('button[data-appointment-id]');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function() {
                const appointmentId = this.dataset.appointmentId;
                confirmCancelAppointment(appointmentId);
            });
        });

        const appointmentModal = document.getElementById('appointmentModal');
        const closeModalButton = document.getElementById('closeModalButton');
        const addNoteButton = document.getElementById('addNoteButton'); // Get the add note button

        if (closeModalButton) {
            closeModalButton.addEventListener('click', function() {
                appointmentModal.classList.add('hidden');
            });
        }

        if (appointmentModal) {
            appointmentModal.addEventListener('click', function(event) {
                if (event.target === appointmentModal) {
                    appointmentModal.classList.add('hidden');
                }
            });
        }

        const noteModal = document.getElementById('noteModal');
        const closeNoteModalButton = document.getElementById('closeNoteModalButton');
        const noteForm = document.getElementById('noteForm');
        const noteAppointmentIdField = document.getElementById('noteAppointmentId');
        const appointmentNoteTextarea = document.getElementById('appointmentNote');

        if (addNoteButton) {
            addNoteButton.addEventListener('click', function() {
                noteAppointmentIdField.value = appointmentModal.dataset.currentAppointmentId;
                appointmentNoteTextarea.value = document.getElementById('modalAppointmentNote').textContent === 'Tidak ada' ? '' : document.getElementById('modalAppointmentNote').textContent;

                appointmentModal.classList.add('hidden'); 
                noteModal.classList.remove('hidden');
            });
        }

        if (closeNoteModalButton) {
            closeNoteModalButton.addEventListener('click', function() {
                noteModal.classList.add('hidden');
            });
        }

        if (noteModal) {
            noteModal.addEventListener('click', function(event) {
                if (event.target === noteModal) {
                    noteModal.classList.add('hidden');
                }
            });
        }


        if (noteForm) {
            noteForm.addEventListener('submit', function(event) {
                event.preventDefault(); 

                const appointmentId = noteAppointmentIdField.value;
                const noteContent = appointmentNoteTextarea.value;

                saveAppointmentNote(appointmentId, noteContent);
            });
        }
    });

    let currentAppointmentId = null;

    function openAppointmentDetailsModal(doctorName, doctorSpecialty, appointmentDate, patientName, consultationTime, queueNumber, appointmentId, existingNote) {
        currentAppointmentId = appointmentId; 
        const appointmentModal = document.getElementById('appointmentModal');
        document.getElementById('modalDoctorName').textContent = doctorName;
        document.getElementById('modalDoctorSpecialty').textContent = doctorSpecialty;
        document.getElementById('modalAppointmentDate').textContent = appointmentDate;
        document.getElementById('modalPatientName').textContent = patientName;
        document.getElementById('modalConsultationTime').textContent = consultationTime;
        document.getElementById('modalQueueNumber').textContent = queueNumber;
        document.getElementById('modalAppointmentNote').textContent = existingNote || 'Tidak ada'; 
        appointmentModal.dataset.currentAppointmentId = appointmentId; 

        appointmentModal.classList.remove('hidden');
    }

    function saveAppointmentNote(appointmentId, noteContent) {
        const url = "{{ route('user.appointments.saveNotes', ['appointment' => 'TEMP_ID']) }}".replace('TEMP_ID', appointmentId);

        fetch(url, {
            method: 'POST', 
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json' 
            },
            body: JSON.stringify({ notes: noteContent })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(error => Promise.reject(error));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire(
                    'Berhasil!',
                    data.message,
                    'success'
                ).then(() => {
                    noteModal.classList.add('hidden'); 
                    location.reload(); 
                });
            } else {
                Swal.fire(
                    'Gagal!',
                    data.message || 'Gagal menyimpan catatan.',
                    'error'
                );
            }
        })
        .catch(error => {
            console.error('Error saving note:', error);
            let errorMessage = 'Terjadi kesalahan saat menyimpan catatan.';
            if (error.message) {
                errorMessage = error.message;
            } else if (error.status === 403) {
                errorMessage = 'Anda tidak memiliki izin untuk menyimpan catatan ini.';
            }
            Swal.fire(
                'Error!',
                errorMessage,
                'error'
            );
        });
    }

    // Your existing confirmCancelAppointment function
    function confirmCancelAppointment(appointmentId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Status janji temu ini akan diubah menjadi Dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, batalkan!',
            cancelButtonText: 'Tidak',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const updateUrl = "{{ route('user.appointments.destroy', ['appointment' => 'TEMP_ID']) }}".replace('TEMP_ID', appointmentId);

                fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(error => Promise.reject(error));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message || 'Janji temu berhasil dibatalkan.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Gagal!',
                            data.message || 'Pembatalan janji temu gagal.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'Terjadi kesalahan saat membatalkan janji temu.';
                    if (error.message) {
                        errorMessage = error.message;
                    } else if (error.status === 403) {
                        errorMessage = 'Anda tidak memiliki izin untuk melakukan tindakan ini.';
                    }
                    Swal.fire(
                        'Error!',
                        errorMessage,
                        'error'
                    );
                });
            }
        });
    }
</script>
@endpush