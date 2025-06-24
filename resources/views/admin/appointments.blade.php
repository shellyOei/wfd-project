@extends('admin.layout')

@section('page-title')
Appointments
@endsection

@section('head')
<style>
    /* Style for the modal backdrop */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
        transition: opacity 0.3s ease-in-out;
    }
    /* Scrollbar styling for better aesthetics */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    .dataTables_wrapper .dataTables_length select
    {
        width: 60px;
        padding: 4px 10px;
    }
    .validation-error {
        color: #e3342f; /* text-red-600 */
        font-size: 0.875rem; /* text-sm */
        margin-top: 0.25rem;
    }
</style>
@endsection

@section('content')
<main class="flex-1 p-6 lg:p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Appointment Management</h1>
            <p class="mt-1 text-gray-500">Oversee, schedule, and manage all patient appointments.</p>
        </div>
        <button onclick="openNewAppointmentModal()" type="button" class="mt-4 md:mt-0 flex items-center bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
            <i class="fas fa-plus mr-2"></i>
            New Appointment
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full"><i class="fas fa-calendar-check fa-lg text-blue-600"></i></div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm font-medium">Ongoing</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statusCounts['ongoing'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-full"><i class="fas fa-calendar-times fa-lg text-red-600"></i></div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm font-medium">Cancelled</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statusCounts['cancelled'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full"><i class="fas fa-check-circle fa-lg text-green-600"></i></div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm font-medium">Completed</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statusCounts['completed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">All Appointments</h2>
        <div class="overflow-x-auto">
            <table id="appointments-table" class="w-full text-sm text-left text-gray-500 mt-2">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Appt. Number</th>
                        <th scope="col" class="px-6 py-3">Patient</th>
                        <th scope="col" class="px-6 py-3">Schedule</th>
                        <th scope="col" class="px-6 py-3">Queue #</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        @php
                            $status = match($appointment->status) {
                                1 => ['label' => 'Ongoing', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'dot' => 'bg-blue-500'],
                                2 => ['label' => 'Cancelled', 'bg' => 'bg-red-100', 'text' => 'text-red-800', 'dot' => 'bg-red-500'],
                                3 => ['label' => 'Completed', 'bg' => 'bg-green-100', 'text' => 'text-green-800', 'dot' => 'bg-green-500'],
                                default => ['label' => 'Unknown', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'dot' => 'bg-gray-500'],
                            };
                        @endphp
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $appointment->appointment_number ?? '-' }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $appointment->patient->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                {{ $appointment->schedule->dayAvailable->doctor->name ?? 'Unknown Doctor' }} -
                                {{ \Carbon\Carbon::parse($appointment->schedule->Datetime)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 font-mono">{{ $appointment->queue_number ?? '-' }}</td>
                            <td class="px-6 py-4">{{ ucfirst($appointment->type) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center {{ $status['bg'] }} {{ $status['text'] }} text-xs font-medium px-2.5 py-1 rounded-full">
                                    <span class="w-2 h-2 mr-1 {{ $status['dot'] }} rounded-full"></span>
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="openViewModal(this)" data-appointment='{{ json_encode($appointment) }}' class="font-medium text-blue-600 hover:underline mr-3"><i class="fas fa-eye"></i> View</button>
                                @if ($appointment->status != 2 && $appointment->status != 3)
                                    <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" class="inline-block cancel-form">
                                        @csrf
                                        <button type="submit" class="font-medium text-red-600 hover:underline"><i class="fas fa-times-circle"></i> Cancel</button>
                                    </form>
                                @else
                                    <button class="font-medium text-gray-400 cursor-not-allowed" disabled><i class="fas fa-times-circle"></i> Cancel</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center px-6 py-4 text-gray-500">No appointments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="newAppointmentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden modal-backdrop">
    <div class="bg-white rounded-xl shadow-2xl w-3/4 max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
        <form id="new-appointment-form" action="{{ route('admin.appointments.store') }}" method="POST">
            @csrf
            <div class="flex justify-between items-center p-5 border-b">
                <h3 class="text-xl font-bold text-gray-800">Create New Appointment</h3>
                <button type="button" onclick="closeNewAppointmentModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times fa-lg"></i></button>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label for="new-patient-select" class="block text-sm font-medium text-gray-700">Patient</label>
                        <select id="new-patient-select" name="patient_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Select a patient...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                            @endforeach
                        </select>
                        <div id="error-patient_id" class="validation-error"></div>
                    </div>
                    <div>
                        <label for="new-doctor-select" class="block text-sm font-medium text-gray-700">Doctor</label>
                        <select id="new-doctor-select" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Select a doctor...</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="new-day-select" class="block text-sm font-medium text-gray-700">Day of Week</label>
                        <select id="new-day-select" name="day_available_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled required>
                            <option value="">Select a doctor first</option>
                        </select>
                        <div id="error-day_available_id" class="validation-error"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="new-date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" id="new-date" name="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled required>
                            <div id="error-date" class="validation-error"></div>
                        </div>
                        <div>
                            <label for="new-time" class="block text-sm font-medium text-gray-700">Time</label>
                            <input type="time" id="new-time" name="time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled required>
                            <div id="error-time" class="validation-error"></div>
                        </div>
                    </div>
                    <div>
                        <label for="new-type" class="block text-sm font-medium text-gray-700">Appointment Type</label>
                        <input type="text" id="new-type" name="type" placeholder="e.g., Check-up, Consultation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <div id="error-type" class="validation-error"></div>
                    </div>
                    <div class="flex items-center">
                        <input id="new-bpjs" name="is_bpjs" type="checkbox" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="new-bpjs" class="ml-2 block text-sm text-gray-900">Patient is using BPJS</label>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center p-5 border-t bg-gray-50 rounded-b-xl">
                <button type="button" onclick="closeNewAppointmentModal()" class="bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-300">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 ml-2"><i class="fas fa-plus mr-2"></i>Create Appointment</button>
            </div>
        </form>
    </div>
</div>

<div id="viewModal" class="fixed inset-0 z-50 flex items-center justify-center hidden modal-backdrop">...</div>
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center hidden modal-backdrop">...</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // DataTable and existing modal logic (open/close, view/edit population, cancel)
    // ... This part remains the same as the previous response ...
    $(document).ready(function () {
        $('#appointments-table').DataTable({ /* ...options... */ });
        $('#appointments-table tbody').on('submit', '.cancel-form', function (event) { /* ...logic... */ });
    });
    const viewModal = document.getElementById('viewModal');
    const editModal = document.getElementById('editModal');
    function openViewModal(buttonElement) { /* ...logic... */ }
    function closeViewModal() { /* ...logic... */ }
    function openEditModal(appointment) { /* ...logic... */ }
    function closeEditModal() { /* ...logic... */ }


    // --- New Appointment Modal Logic ---
    const newAppointmentModal = document.getElementById('newAppointmentModal');
    const newAppointmentForm = document.getElementById('new-appointment-form');

    function openNewAppointmentModal() {
        newAppointmentForm.reset();
        clearValidationErrors();
        document.getElementById('new-day-select').innerHTML = '<option value="">Select a doctor first</option>';
        document.getElementById('new-day-select').disabled = true;
        document.getElementById('new-date').disabled = true;
        document.getElementById('new-time').disabled = true;
        newAppointmentModal.classList.remove('hidden');
    }

    function closeNewAppointmentModal() {
        newAppointmentModal.classList.add('hidden');
    }

    function clearValidationErrors() {
        document.querySelectorAll('.validation-error').forEach(el => el.textContent = '');
    }

    // --- Event Listeners ---
    document.addEventListener('click', function(event) {
        if (event.target === newAppointmentModal) closeNewAppointmentModal();
        if (event.target === viewModal) closeViewModal();
        if (event.target === editModal) closeEditModal();
    });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeNewAppointmentModal();
            closeViewModal();
            closeEditModal();
        }
    });

    // --- New Appointment Dependent Dropdown and Form Submission Logic ---
    document.addEventListener('DOMContentLoaded', () => {
        const doctorSelect = document.getElementById('new-doctor-select');
        const daySelect = document.getElementById('new-day-select');
        const dateInput = document.getElementById('new-date');
        const timeInput = document.getElementById('new-time');

        doctorSelect.addEventListener('change', function() {
            const doctorId = this.value;
            daySelect.innerHTML = '<option value="">Loading days...</option>';
            daySelect.disabled = true;
            dateInput.disabled = true;
            timeInput.disabled = true;

            if (!doctorId) {
                daySelect.innerHTML = '<option value="">Select a doctor first</option>';
                return;
            }

            // NOTE: Ensure this route returns the 'DayAvailable' models for the doctor.
            // Example JSON response: [{"id":"uuid-1","day":"Monday"}, {"id":"uuid-2","day":"Wednesday"}]
            const urlTemplate = "{{ route('admin.appointments.schedules', ['doctor' => ':doctorId']) }}";
            const url = urlTemplate.replace(':doctorId', doctorId);
            
            fetch(url)
                .then(response => response.json())
                .then(days => {
                    daySelect.innerHTML = '';
                    if (days.length > 0) {
                        daySelect.innerHTML = '<option value="">Select an available day</option>';
                        // 'day' in the response should be the text (e.g., 'Monday'),
                        // 'value' should be the 'day_available_id'
                        days.forEach(day => {
                            const option = document.createElement('option');
                            option.value = day.value; 
                            option.textContent = day.text;
                            daySelect.appendChild(option);
                        });
                        daySelect.disabled = false;
                        dateInput.disabled = false;
                        timeInput.disabled = false;
                    } else {
                        daySelect.innerHTML = '<option value="">No available days found</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching available days:', error);
                    daySelect.innerHTML = '<option value="">Could not load days</option>';
                });
        });

        // AJAX Form Submission
        newAppointmentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            clearValidationErrors();

            const formData = new FormData(this);
            const actionUrl = this.action;

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeNewAppointmentModal();
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect_url;
                    });
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        for (const key in data.errors) {
                            const errorElement = document.getElementById(`error-${key}`);
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                            }
                        }
                    }
                    // Handle general error message
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Could not create the appointment.',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Submission Error:', error);
                Swal.fire({
                    title: 'Request Failed!',
                    text: 'An unexpected server error occurred. Please try again.',
                    icon: 'error'
                });
            });
        });
    });
</script>
@endsection