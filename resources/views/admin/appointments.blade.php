@extends('admin.layout')

@section('head')
{{-- searchable dropdown --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- advanced date picker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

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
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-calendar-check fa-lg text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm font-medium">Ongoing</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $statusCounts['ongoing'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-calendar-times fa-lg text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm font-medium">Cancelled</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $statusCounts['cancelled'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle fa-lg text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm font-medium">Completed</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $statusCounts['completed'] }}</p>
                    </div>
                </div>
            </div>
            {{-- <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-hourglass-half fa-lg text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm font-medium">Pending</p>
                        <p class="text-2xl font-bold text-gray-800">8</p>
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
           <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800 mb-4 md:mb-0">All Appointments</h2>
                <div class="flex items-center space-x-2 w-full md:w-auto">
                    <select id="appointment-status-filter" class="border rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option> <option value="Ongoing">Ongoing</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
            </div>
                        
            <div class="overflow-x-auto">
                <table id="appointments-table" class="w-full text-sm text-left text-gray-500 mt-2">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Appointment Number</th>
                            <th scope="col" class="px-6 py-3">Patient</th>
                            <th scope="col" class="px-6 py-3">Schedule</th>
                            <th scope="col" class="px-6 py-3">Queue #</th>
                            <th scope="col" class="px-6 py-3">Type</th>
                            <th scope="col" class="px-6 py-3">BPJS</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="appointmentTableBody">
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
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $appointment->appointment_number ?? '-' }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $appointment->patient->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $appointment->schedule->dayAvailable->doctor->name ?? 'Unknown Doctor' }} -
                                    {{ \Carbon\Carbon::parse($appointment->schedule->Datetime)->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 font-mono">
                                    {{ $appointment->queue_number ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ ucfirst($appointment->type) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="{{ $appointment->is_bpjs ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {{ $appointment->is_bpjs ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center {{ $status['bg'] }} {{ $status['text'] }} text-xs font-medium px-2.5 py-1 rounded-full">
                                        <span class="w-2 h-2 mr-1 {{ $status['dot'] }} rounded-full"></span>
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="openViewModal(this)" 
                                    data-appointment='{{ json_encode($appointment) }}' class="font-medium text-blue-600 hover:underline mr-3">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    @if ($appointment->status != 2 && $appointment->status != 3) {{-- only show for ongoing appointments --}}
                                        <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" class="inline-block cancel-form">
                                            @csrf
                                            <button type="submit" class="font-medium text-red-600 hover:underline">
                                                <i class="fas fa-times-circle"></i> Cancel
                                            </button>
                                        </form>
                                    @else
                                        <button class="font-medium text-gray-400 cursor-not-allowed" disabled>
                                            <i class="fas fa-times-circle"></i> Cancel
                                        </button>
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
            <form id="newAppointmentForm" action="{{ route('admin.appointments.store') }}" method="POST">
                @csrf
                <div class="flex justify-between items-center p-5 border-b">
                    <h3 class="text-xl font-bold text-gray-800">Create New Appointment</h3>
                    <button type="button" onclick="closeNewAppointmentModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times fa-lg"></i></button>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label for="new-patient-select" class="block text-sm font-medium text-gray-700">Patient (Search by Name or Patient Number)</label>
                            {{-- This select will be enhanced by Select2 --}}
                            <select id="new-patient-select" name="patient_id" class="mt-1 block w-full" required>
                                <option value="">Select a patient...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }} - ({{ $patient->patient_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="new-doctor-select" class="block text-sm font-medium text-gray-700">Doctor</label>
                            <select id="new-doctor-select" name="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Select a doctor...</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="appointment-date" class="block text-sm font-medium text-gray-700">Appointment Date</label>
                            {{-- This input will be replaced by Flatpickr --}}
                            <input type="text" id="appointment-date" name="date" placeholder="Select a doctor first" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled required>
                        </div>

                        <div>
                            <label for="time-select" class="block text-sm font-medium text-gray-700">Available Time</label>
                            <select id="time-select" name="time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled required>
                                <option value="">Select a date first</option>
                            </select>
                        </div>

                        <input type="hidden" name="day_available_id" id="day_available_id">

                        <div>
                            <label for="new-type" class="block text-sm font-medium text-gray-700">Appointment Type</label>
                            <input type="text" id="new-type" name="type" placeholder="e.g., Check-up, Consultation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="flex items-center">
                            <input id="new-bpjs" name="is_bpjs" type="checkbox" value="true" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="new-bpjs" class="ml-2 block text-sm text-gray-900">Patient is using BPJS</label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end items-center p-5 border-t bg-gray-50 rounded-b-xl">
                    <button type="button" onclick="closeNewAppointmentModal()" class="bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md"><i class="fas fa-plus mr-2"></i>Create Appointment</button>
                </div>
            </form>
        </div>
    </div>

    <div id="viewModal" class="fixed inset-0 z-50 flex items-center justify-center hidden modal-backdrop">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-5 border-b">
                <h3 class="text-xl font-bold text-gray-800">Appointment Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times fa-lg"></i></button>
            </div>
            <div class="p-6">
                {{-- Main Details Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div class="space-y-4">
                        <div><label class="text-sm font-medium text-gray-500">Patient</label><p id="view-patient-name" class="text-lg font-semibold text-gray-900"></p></div>
                        <div><label class="text-sm font-medium text-gray-500">Schedule</label><p id="view-schedule" class="text-md text-gray-700"></p></div>
                        <div><label class="text-sm font-medium text-gray-500">Queue / Type</label><p id="view-queue-type" class="text-md text-gray-700"></p></div>
                        <div><label class="text-sm font-medium text-gray-500">Insurance</label><p id="view-insurance" class="text-md text-gray-700"></p></div>
                    </div>
                    {{-- Right Column --}}
                    <div class="space-y-4">
                        <div><label class="text-sm font-medium text-gray-500">Appointment Number</label><p id="view-appointment-number" class="text-md font-semibold text-gray-900"></p></div>
                        <div><label class="text-sm font-medium text-gray-500">Status</label><div id="view-status-container"></div></div>
                    </div>
                </div>

                {{-- SOAP Notes Section --}}
                <div class="mt-6 pt-6 border-t">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">SOAP Notes</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div><label class="font-semibold text-gray-700">Subjective</label><p id="view-subjective" class="text-gray-600 mt-1"></p></div>
                        <div><label class="font-semibold text-gray-700">Objective</label><p id="view-objective" class="text-gray-600 mt-1"></p></div>
                        <div><label class="font-semibold text-gray-700">Assessment</label><p id="view-assessment" class="text-gray-600 mt-1"></p></div>
                        <div><label class="font-semibold text-gray-700">Plan</label><p id="view-plan" class="text-gray-600 mt-1"></p></div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center p-5 border-t bg-gray-50 rounded-b-xl">
                <button onclick="closeViewModal()" class="bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-300 mr-2">Close</button>
                <button id="view-modal-edit-button" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-300"><i class="fas fa-edit mr-2"></i>Edit Appointment</button>
            </div>
        </div>
    </div>
    
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center hidden modal-backdrop">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
            {{-- Give the form an ID and add Laravel's method directives --}}
            <form id="edit-appointment-form" method="POST">
                @csrf
                @method('PUT')

                <div class="flex justify-between items-center p-5 border-b">
                    <h3 class="text-xl font-bold text-gray-800">Edit Appointment</h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times fa-lg"></i></button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Left Column: Patient, Schedule, Details --}}
                        <div class="space-y-4">
                            <div><label for="edit-patient" class="block text-sm font-medium text-gray-700">Patient</label><input type="text" id="edit-patient" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 sm:text-sm" disabled></div>
                            
                            {{-- Schedule is complex to edit, so we display it as non-editable for now --}}
                            <div><label for="edit-schedule-display" class="block text-sm font-medium text-gray-700">Schedule</label><input type="text" id="edit-schedule-display" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 sm:text-sm" disabled></div>

                            <div><label for="edit-type" class="block text-sm font-medium text-gray-700">Appointment Type</label><input type="text" id="edit-type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></div>
                            
                            <div><label for="edit-status" class="block text-sm font-medium text-gray-700">Status</label><select id="edit-status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"><option value="1">Ongoing</option><option value="2">Cancelled</option><option value="3">Completed</option></select></div>
                            
                            <div class="flex items-center"><input id="edit-bpjs" name="is_bpjs" type="checkbox" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"><label for="edit-bpjs" class="ml-2 block text-sm text-gray-900">Patient is using BPJS</label></div>
                        </div>
                        {{-- Right Column: SOAP Notes --}}
                        <div class="space-y-4">
                            <div><label for="edit-subjective" class="block text-sm font-medium text-gray-700">Subjective</label><textarea id="edit-subjective" name="subjective" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea></div>
                            <div><label for="edit-objective" class="block text-sm font-medium text-gray-700">Objective</label><textarea id="edit-objective" name="objective" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea></div>
                            <div><label for="edit-assessment" class="block text-sm font-medium text-gray-700">Assessment</label><textarea id="edit-assessment" name="assessment" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea></div>
                            <div><label for="edit-plan" class="block text-sm font-medium text-gray-700">Plan</label><textarea id="edit-plan" name="plan" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end items-center p-5 border-t bg-gray-50 rounded-b-xl">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-300 mr-2">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-green-700 transition duration-300"><i class="fas fa-save mr-2"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('script')
{{-- Make sure SweetAlert2 is included --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Make sure Flatpickr CSS is included if not already --}}
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}
{{-- Make sure Flatpickr JS is included --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}

<script>
    // --- Global Variables (Accessible to all functions in this script) ---
    let datePicker = null; // Flatpickr instance
    let newAppointmentForm, doctorSelect, dateInput, timeSelect, dayAvailableIdInput;
    let newAppointmentModal, viewModal, editModal; // Modal elements

    // --- Helper Functions (Defined outside $(document).ready for global accessibility) ---

    function resetDateAndTime() {
        if (datePicker) {
            datePicker.destroy(); // Remove old datepicker instance
            datePicker = null;
        }
        if (dateInput) { // Ensure dateInput exists before accessing
            dateInput.value = '';
            dateInput.placeholder = 'Select a doctor first';
            dateInput.disabled = true;
        }
        resetTime();
    }

    function resetTime() {
        if (timeSelect) { // Ensure timeSelect exists before accessing
            timeSelect.innerHTML = '<option value="">Select a date first</option>';
            timeSelect.disabled = true;
        }
        if (dayAvailableIdInput) { // Ensure dayAvailableIdInput exists before accessing
            dayAvailableIdInput.value = '';
        }
    }

    function fetchAvailableTimes(doctorId, dateStr) {
        resetTime();
        if (!dateStr) return;

        timeSelect.innerHTML = '<option value="">Loading times...</option>';

        const baseUrl = "{{ route('admin.doctors.available-times', ['doctor' => '__DOCTOR_ID__', 'date' => '__DATE__']) }}";

        let url = baseUrl.replace('__DOCTOR_ID__', doctorId);
        url = url.replace('__DATE__', dateStr); 

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.available_slots && data.available_slots.length > 0) {
                    timeSelect.innerHTML = '<option value="">Select an available time</option>';
                    data.available_slots.forEach(time => {
                        const option = new Option(time, time);
                        timeSelect.appendChild(option);
                    });
                    timeSelect.disabled = false;
                    dayAvailableIdInput.value = data.day_available_id; // IMPORTANT
                } else {
                    timeSelect.innerHTML = '<option value="">No available times for this date</option>';
                }
            })
            .catch(error => {
                console.error('Error fetching available times:', error);
                timeSelect.innerHTML = '<option value="">Error loading times</option>';
            });
    }

    // --- Modal Functions ---
    // Make sure these modal elements are correctly identified in the DOM.
    function openNewAppointmentModal() {
        // Reset form fields
        if (newAppointmentForm) newAppointmentForm.reset();
        // Reset Select2 for patient dropdown
        if ($('#new-patient-select').length) {
            $('#new-patient-select').val(null).trigger('change');
        }
        // Reset date and time fields via helper function
        resetDateAndTime();
        // Show the modal
        if (newAppointmentModal) newAppointmentModal.classList.remove('hidden');
    }

    function closeNewAppointmentModal() {
        if (newAppointmentModal) newAppointmentModal.classList.add('hidden');
    }

    function openViewModal(buttonElement) {
        const appointmentJson = buttonElement.dataset.appointment;
        const appointment = JSON.parse(appointmentJson);

        // ... (your existing view modal population logic) ...
        document.getElementById('view-patient-name').innerText = appointment.patient?.name ?? 'N/A';
        const scheduleDate = new Date(appointment.schedule?.Datetime).toLocaleString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true });
        document.getElementById('view-schedule').innerText = `${appointment.schedule?.day_available?.doctor?.name ?? 'Unknown Doctor'} - ${scheduleDate}`;
        document.getElementById('view-queue-type').innerHTML = `<span class="font-mono bg-gray-100 px-2 py-1 rounded">${appointment.queue_number ?? '-'}</span> / ${appointment.type ?? 'N/A'}`;
        document.getElementById('view-insurance').innerHTML = appointment.is_bpjs ? 'BPJS <span class="text-green-600">(Yes)</span>' : 'General <span class="text-gray-600">(No)</span>';
        document.getElementById('view-appointment-number').innerText = appointment.appointment_number ?? 'N/A';
        document.getElementById('view-subjective').innerText = appointment.subjective ?? 'No subjective notes provided.';
        document.getElementById('view-objective').innerText = appointment.objective ?? 'No objective notes provided.';
        document.getElementById('view-assessment').innerText = appointment.assessment ?? 'No assessment provided.';
        document.getElementById('view-plan').innerText = appointment.plan ?? 'No plan provided.';

        // Status logic can remain here
        let statusDetails = { label: 'Unknown', bg: 'bg-gray-100', text: 'text-gray-800', dot: 'bg-gray-400' };
        switch (appointment.status) {
            case 1: statusDetails = { label: 'Ongoing', bg: 'bg-blue-100', text: 'text-blue-800', dot: 'bg-blue-500' }; break;
            case 2: statusDetails = { label: 'Cancelled', bg: 'bg-red-100', text: 'text-red-800', dot: 'bg-red-500' }; break;
            case 3: statusDetails = { label: 'Completed', bg: 'bg-green-100', text: 'text-green-800', dot: 'bg-green-500' }; break;
        }
        document.getElementById('view-status-container').innerHTML = `<p class="text-md font-semibold"><span class="inline-flex items-center ${statusDetails.bg} ${statusDetails.text} text-md font-medium px-3 py-1 rounded-full"><span class="w-2 h-2 mr-2 ${statusDetails.dot} rounded-full"></span>${statusDetails.label}</span></p>`;

        const editButton = document.getElementById('view-modal-edit-button');
        if (editButton) editButton.onclick = () => openEditModal(appointment);
        if (viewModal) viewModal.classList.remove('hidden');
    }

    function closeViewModal() {
        if (viewModal) viewModal.classList.add('hidden');
    }

    function openEditModal(appointment) {
        closeViewModal();
        const editForm = document.getElementById('edit-appointment-form');
        if (editForm) {
            const urlTemplate = "{{ route('admin.appointments.update', ['appointment' => ':id']) }}";
            const actionUrl = urlTemplate.replace(':id', appointment.id);
            editForm.action = actionUrl;

            // ... (your existing edit modal population logic) ...
            document.getElementById('edit-patient').value = appointment.patient?.name ?? 'N/A';
            const scheduleDate = new Date(appointment.schedule?.Datetime).toLocaleString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true });
            document.getElementById('edit-schedule-display').value = `${appointment.schedule?.day_available?.doctor?.name} - ${scheduleDate}`;
            document.getElementById('edit-type').value = appointment.type ?? '';
            document.getElementById('edit-status').value = appointment.status;
            document.getElementById('edit-bpjs').checked = appointment.is_bpjs;
            document.getElementById('edit-subjective').value = appointment.subjective ?? '';
            document.getElementById('edit-objective').value = appointment.objective ?? '';
            document.getElementById('edit-assessment').value = appointment.assessment ?? '';
            document.getElementById('edit-plan').value = appointment.plan ?? '';
        }
        if (editModal) editModal.classList.remove('hidden');
    }

    function closeEditModal() {
        if (editModal) editModal.classList.add('hidden');
    }


    // --- Document Ready / DOMContentLoaded equivalent (using jQuery's ready) ---
    $(document).ready(function () {
        // --- Initialize global variables once DOM is ready ---
        newAppointmentForm = document.getElementById('newAppointmentForm');
        doctorSelect = document.getElementById('new-doctor-select');
        dateInput = document.getElementById('appointment-date');
        timeSelect = document.getElementById('time-select');
        dayAvailableIdInput = document.getElementById('day_available_id');
        newAppointmentModal = document.getElementById('newAppointmentModal');
        viewModal = document.getElementById('viewModal');
        editModal = document.getElementById('editModal');


        // --- DataTables Initialization ---
        appointmentsTable = $('#appointments-table').DataTable({ // Assign to global variable
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ appointments",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                },
                zeroRecords: "No matching appointments found"
            },  
        });

        // --- Select2 Initialization ---
        $('#new-patient-select').select2({
            dropdownParent: $('#newAppointmentModal'), // Attach dropdown to the modal
            width: '100%'
        });

        // --- Event Listener for Doctor Selection ---
        if (doctorSelect) {
            doctorSelect.addEventListener('change', function() {
                const doctorId = this.value;
                resetDateAndTime(); 

                if (!doctorId) return;
                const doctorAvailabilityBaseUrl = "{{ route('admin.doctors.availability', ['doctor' => '__DOCTOR_ID__']) }}";

                const url = doctorAvailabilityBaseUrl.replace('__DOCTOR_ID__', doctorId);


                fetch(url)
                    .then(response => response.json())
                    .then(availableDays => {
                        // Initialize Flatpickr (the date picker)
                        if (dateInput) {
                            datePicker = flatpickr(dateInput, {
                                dateFormat: "Y-m-d",
                                minDate: "today",
                                // CRITICAL: Only enable the days of the week the doctor works
                                "enable": [
                                    function(date) {
                                        // `date.getDay()` returns 0 for Sun, 1 for Mon...
                                        // The API returns 0 for Sun, 1 for Mon...
                                        return availableDays.includes(date.getDay());
                                    }
                                ],
                                // When a user selects a date, trigger the time fetch
                                onChange: function(selectedDates, dateStr, instance) {
                                    fetchAvailableTimes(doctorId, dateStr);
                                }
                            });

                            dateInput.placeholder = 'Select an available date';
                            dateInput.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching doctor availability:', error);
                        if (dateInput) dateInput.placeholder = 'Could not load schedule';
                    });
            });
        }


        // --- Form Submission using AJAX ---
        if (newAppointmentForm) {
            newAppointmentForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const formData = new FormData(this);
                const actionUrl = this.action;
                const csrfTokenElement = document.querySelector('input[name="_token"]');
                const csrfToken = csrfTokenElement ? csrfTokenElement.value : '';

                fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeNewAppointmentModal(); // Call the globally defined function
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    } else {
                        let errorHtml = data.message;
                        if (data.errors) {
                            errorHtml += '<ul class="text-left text-sm list-disc list-inside mt-2">';
                            Object.values(data.errors).forEach(err => {
                                errorHtml += `<li>${err[0]}</li>`;
                            });
                            errorHtml += '</ul>';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Creation Failed',
                            html: errorHtml,
                        });
                    }
                })
                .catch(error => {
                    console.error('Submission Error:', error);
                    Swal.fire('Request Failed!', 'An unexpected error occurred.', 'error');
                });
            });
        }


        // --- Cancel Form Submission using AJAX (Delegated Event) ---
        // Use event delegation for dynamically loaded or existing elements
        $('#appointments-table tbody').on('submit', '.cancel-form', function (event) {
            event.preventDefault();

            const form = this;
            const actionUrl = form.action;
            const csrfTokenElement = form.querySelector('input[name="_token"]');
            const csrfToken = csrfTokenElement ? csrfTokenElement.value : '';


            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to cancel this appointment?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Cancelled!',
                                text: data.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                            }).then(() => {
                                location.reload(); // Reload page on success
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Request Failed!',
                            text: 'An unexpected network error occurred. Please try again.',
                            icon: 'error'
                        });
                    });
                }
            });
        });

        // --- Event Listener for Status Filter ---
        // Get the filter select element
        const statusFilterSelect = document.getElementById('appointment-status-filter');

        if (statusFilterSelect) {
            statusFilterSelect.addEventListener('change', function() {
                const selectedStatus = this.value; 

                if (selectedStatus === "") {
                    appointmentsTable.column(6).search('').draw();
                } else {
                    appointmentsTable.column(6).search(selectedStatus).draw();

                }
            });
        }
        // --- Event Listeners to close modals (delegated from document) ---
        // Ensuring modals are defined before adding listeners
        if (newAppointmentModal) {
            document.addEventListener('click', function(event) {
                if (event.target === newAppointmentModal) closeNewAppointmentModal();
            });
        }
        if (viewModal) {
            document.addEventListener('click', function(event) {
                if (event.target === viewModal) closeViewModal();
            });
        }
        if (editModal) {
            document.addEventListener('click', function(event) {
                if (event.target === editModal) closeEditModal();
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeViewModal();
                closeEditModal();
                closeNewAppointmentModal();
            }
        });
    }); // End of $(document).ready
</script>
@endsection