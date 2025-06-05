@extends('admin.layout')

@section('title', 'Patients')
@section('page-title', 'Patients Management')

@section('content')
<style>
    .dataTables_wrapper .dataTables_length select {
    border: 1px solid #aaa;
    border-radius: 3px;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 20px;
    padding-right: 20px;
    background-color: transparent;
    color: inherit;
}
</style>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Patients</h1>
                <p class="text-gray-600 mt-1">Manage patient information and medical records</p>
            </div>
            <div class="flex space-x-3">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add New Patient
                </button>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text"
                           placeholder="Search patients..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div class="flex space-x-3">
                <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
                <select class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option>All Blood Types</option>
                    <option>A+</option>
                    <option>A-</option>
                    <option>B+</option>
                    <option>B-</option>
                    <option>AB+</option>
                    <option>AB-</option>
                    <option>O+</option>
                    <option>O-</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">All Patients ({{ $patients->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="patientsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Patient</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Date of Birth</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-700">Linked Users</th>
                        <th class="text-center py-4 px-6 font-medium text-gray-700">Medical History</th>
                        <th class="text-center py-4 px-6 font-medium text-gray-700">Status</th>
                        <th class="text-center py-4 px-6 font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <!-- Patient Info -->
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-user text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $patient->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $patient->patient_number }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst($patient->sex) }} • {{ $patient->blood_type }}{{ $patient->rhesus_factor }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Date of Birth -->
                            <td class="py-4 px-6">
                                <div>
                                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-600">Age: {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} years</p>
                                </div>
                            </td>

                            <!-- Linked Users -->
                            <td class="py-4 px-6">
                                @if($patient->profiles && $patient->profiles->count() > 0)
                                    <div class="space-y-1">
                                        @foreach($patient->profiles as $profile)
                                            <div class="flex items-center">
                                                <i class="fas fa-link text-green-500 text-xs mr-2"></i>
                                                <span class="text-sm text-gray-700">{{ $profile->user->email ?? 'N/A' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500 italic">No linked users</span>
                                @endif
                            </td>

                            <!-- Medical History -->
                            <td class="py-4 px-6 text-center">
                                <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-sm font-medium transition duration-200 flex items-center mx-auto"
                                        onclick="viewMedicalHistory('{{ $patient->id }}', '{{ $patient->name }}')">
                                    <i class="fas fa-file-medical mr-2"></i>View
                                </button>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 text-center">
                                @php
                                    $hasRecentAppointment = $patient->appointments->where('created_at', '>=', now()->subMonths(6))->count() > 0;
                                    $status = $hasRecentAppointment ? 'active' : 'inactive';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition duration-200"
                                            title="View Details"
                                            onclick="viewPatient('{{ $patient->id }}')">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                    <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition duration-200"
                                            title="Edit Patient"
                                            onclick="editPatient('{{ $patient->id }}')">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition duration-200"
                                            title="Schedule Appointment">
                                        <i class="fas fa-calendar-plus text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-500">No patients found</p>
                                <p class="text-gray-400">Start by adding your first patient</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Patient Modal -->
    <div id="editPatientModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeEditModal()"></div>

            <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Patient Information</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="editPatientForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" id="edit_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" id="edit_phone" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <!-- Sex -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select name="sex" id="edit_sex" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="edit_date_of_birth" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <!-- Blood Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Blood Type</label>
                            <select name="blood_type" id="edit_blood_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Blood Type</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>

                        <!-- Rhesus Factor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rhesus Factor</label>
                            <select name="rhesus_factor" id="edit_rhesus_factor"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Rhesus</option>
                                <option value="+">Positive (+)</option>
                                <option value="-">Negative (-)</option>
                            </select>
                        </div>

                        <!-- Occupation -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                            <input type="text" name="occupation" id="edit_occupation" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <!-- ID Card Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Card Number</label>
                            <input type="text" name="id_card_number" id="edit_id_card_number" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <!-- BPJS Number -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Number (Optional)</label>
                            <input type="text" name="BPJS_number" id="edit_BPJS_number"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="address" id="edit_address" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                            Update Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Medical History Modal -->
    <div id="medicalHistoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeMedicalHistoryModal()"></div>

            <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Medical History - <span id="patientNameHistory"></span></h3>
                    <button onclick="closeMedicalHistoryModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div id="medicalHistoryContent">
                    <!-- Medical history content will be loaded here -->
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Loading medical history...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // Patient data for JavaScript access
    const patients = @json($patients);

    // Edit Patient Modal Functions
    function editPatient(patientId) {
        const patient = patients.find(p => p.id === patientId);
        if (!patient) return;

        // Populate form fields
        document.getElementById('edit_name').value = patient.name;
        document.getElementById('edit_phone').value = patient.phone;
        document.getElementById('edit_sex').value = patient.sex;
        document.getElementById('edit_date_of_birth').value = patient.date_of_birth;
        document.getElementById('edit_blood_type').value = patient.blood_type || '';
        document.getElementById('edit_rhesus_factor').value = patient.rhesus_factor || '';
        document.getElementById('edit_occupation').value = patient.occupation;
        document.getElementById('edit_id_card_number').value = patient.id_card_number;
        document.getElementById('edit_BPJS_number').value = patient.BPJS_number || '';
        document.getElementById('edit_address').value = patient.address;

        // Set form action
        document.getElementById('editPatientForm').action = `/admin/patients/${patientId}`;

        // Show modal
        document.getElementById('editPatientModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editPatientModal').classList.add('hidden');
    }

    // View Patient Details
    function viewPatient(patientId) {
        const patient = patients.find(p => p.id === patientId);
        console.log(patient);
        if (!patient) return;

        Swal.fire({
            title: patient.name,
            html: `
                <div class="text-left space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <strong>Patient Number:</strong><br>
                            <span class="text-gray-600">${patient.patient_number}</span>
                        </div>
                        <div>
                            <strong>Gender:</strong><br>
                            <span class="text-gray-600">${patient.sex.charAt(0).toUpperCase() + patient.sex.slice(1)}</span>
                        </div>
                        <div>
                            <strong>Date of Birth:</strong><br>
                            <span class="text-gray-600">${new Date(patient.date_of_birth).toLocaleDateString()}</span>
                        </div>
                        <div>
                            <strong>Blood Type:</strong><br>
                            <span class="text-gray-600">${patient.blood_type || 'N/A'}${patient.rhesus_factor || ''}</span>
                        </div>
                        <div>
                            <strong>Phone:</strong><br>
                            <span class="text-gray-600">${patient.phone}</span>
                        </div>
                        <div>
                            <strong>Occupation:</strong><br>
                            <span class="text-gray-600">${patient.occupation}</span>
                        </div>
                        <div class="col-span-2">
                            <strong>Address:</strong><br>
                            <span class="text-gray-600">${patient.address}</span>
                        </div>
                        <div>
                            <strong>ID Card Number:</strong><br>
                            <span class="text-gray-600">${patient.id_card_number}</span>
                        </div>
                        <div>
                            <strong>BPJS Number:</strong><br>
                            <span class="text-gray-600">${patient.BPJS_number || 'Not registered'}</span>
                        </div>
                    </div>
                </div>
            `,
            width: '600px',
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'text-sm'
            }
        });
    }

    // Medical History Modal Functions
    function viewMedicalHistory(patientId, patientName) {
        document.getElementById('patientNameHistory').textContent = patientName;
        document.getElementById('medicalHistoryModal').classList.remove('hidden');

        // Simulate loading medical history (replace with actual API call)
        setTimeout(() => {
            const patient = patients.find(p => p.id === patientId);
            const appointmentCount = patient.appointments ? patient.appointments.length : 0;

            document.getElementById('medicalHistoryContent').innerHTML = `
                <div class="space-y-6">
                    <!-- Summary -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-900 mb-2">Medical Summary</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-blue-700">Total Appointments:</span>
                                <span class="font-medium">${appointmentCount}</span>
                            </div>
                            <div>
                                <span class="text-blue-700">Blood Type:</span>
                                <span class="font-medium">${patient.blood_type || 'N/A'}${patient.rhesus_factor || ''}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Appointments -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Recent Appointments</h4>
                        ${appointmentCount > 0 ? `
                            <div class="space-y-3">
                                ${patient.appointments.slice(0, 5).map(appointment => `
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="font-medium text-gray-900">Appointment #${appointment.queue_number || 'N/A'}</span>
                                            <span class="text-sm text-gray-500">${new Date(appointment.created_at).toLocaleDateString()}</span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <p><strong>Type:</strong> ${appointment.type || 'General consultation'}</p>
                                            <p><strong>BPJS:</strong> ${appointment.is_bpjs ? 'Yes' : 'No'}</p>
                                            ${appointment.notes ? `<p><strong>Notes:</strong> ${appointment.notes}</p>` : ''}
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        ` : `
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-3xl mb-3"></i>
                                <p>No appointment history found</p>
                            </div>
                        `}
                    </div>

                    <!-- Lab Results -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Lab Results</h4>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-flask text-3xl mb-3"></i>
                            <p>No lab results available</p>
                        </div>
                    </div>
                </div>
            `;
        }, 1000);
    }

    function closeMedicalHistoryModal() {
        document.getElementById('medicalHistoryModal').classList.add('hidden');
    }

    // Initialize DataTable
    $(document).ready(function() {
        $('#patientsTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [3, 5] } // Disable sorting for Medical History and Actions columns
            ],
            language: {
                search: "Search patients:",
                lengthMenu: "Show _MENU_ patients per page",
                info: "Showing _START_ to _END_ of _TOTAL_ patients",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    });

    // Close modals when clicking outside
    window.onclick = function(event) {
        const editModal = document.getElementById('editPatientModal');
        const historyModal = document.getElementById('medicalHistoryModal');

        if (event.target === editModal) {
            closeEditModal();
        }
        if (event.target === historyModal) {
            closeMedicalHistoryModal();
        }
    }
</script>
@endsection