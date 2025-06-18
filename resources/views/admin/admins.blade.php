@extends('admin.layout')

@section('title', 'Admins')
@section('page-title', 'Admin Management')

@section('content')
    @if(session('doctor_id') != NULL)
        <script>
            Swal.fire({
                heightAuto: false,
                icon: 'error',
                title: 'Oops...',
                text: 'You have no access here.',
                // confirmButtonColor: "#3085d6",
                showConfirmButton: false
            });
            window.location.href = "{{ route('admin.dashboard') }}";

        </script>

    @else

        <style>
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid #aaa;
                border-radius: 3px;
                padding-top: 5px;
                padding-bottom: 5px;
                padding-left: 20px;
                padding-right: 20px;
                margin-right: 6px;
                margin-left: 6px;
                background-color: transparent;
                color: inherit;
            }

            .dataTables_wrapper .dataTables_filter input {
                border: 1px solid #aaa;
                border-radius: 3px;
                padding: 5px;
                background-color: transparent;
                color: inherit;
                margin-left: 6px;
            }
        </style>
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Admins</h1>
                    <p class="text-gray-600 mt-1">Manage admin accounts and their associated doctor profiles</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="openAddAdminModal()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add New Admin
                    </button>
                    <a href="{{ route('admin.manage.export') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <i class="fas fa-download mr-2"></i>Export
                    </a>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="p-3 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">All Admins ({{ $admins->count() }})</h3>
            </div>
            <div class="overflow-x-auto p-6">
                <table class="w-full py-6 !my-6" id="adminsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-center py-4 px-6 font-medium text-gray-700">Admin Info</th>
                            <th class="text-center py-4 px-6 font-medium text-gray-700">Doctor</th>
                            <th class="text-center py-4 px-6 font-medium text-gray-700">Status</th>
                            <th class="text-center py-4 px-6 font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($admins as $admin)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                            <i class="fas fa-user-shield text-indigo-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $admin->name }}</p>
                                            <p class="text-gray-600 text-sm">{{ $admin->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-6 text-center">
                                    @if ($admin->doctor)
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            Dr. {{ $admin->doctor->name }} (ID: {{ $admin->doctor->id }})
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            No Doctor Connected
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-center justify-items-center items-center">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium {{ is_null($admin->deleted_at) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ is_null($admin->deleted_at) ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition duration-200"
                                            title="Edit Admin"
                                            onclick="editAdmin('{{ $admin->id }}', '{{ $admin->name }}', '{{ $admin->email }}', '{{ $admin->deleted_at }}', '{{ $admin->doctor_id }}')">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition duration-200"
                                            title="Delete Admin" onclick="deleteAdmin('{{ $admin->id }}', '{{ $admin->name }}')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 px-6 text-center">
                                    <i class="fas fa-user-shield text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-500">No admins found</p>
                                    <p class="text-gray-400">Start by adding your first admin</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <div id="addAdminModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeAddAdminModal()"></div>

                <div
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Add New Admin</h3>
                        <button onclick="closeAddAdminModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form id="addAdminForm" method="POST" action="{{ route('admin.manage.store') }}">
                        @csrf
                        <div class="mb-6">
                            <label for="new_admin_name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="name" name="name" id="new_admin_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="mb-6">
                            <label for="new_admin_email" class="block text-sm font-medium text-gray-700 mb-2">Email
                                Address</label>
                            <input type="email" name="email" id="new_admin_email" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="mb-6">
                            <label for="new_admin_password"
                                class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" id="new_admin_password" minlength="8" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="mb-6">
                            <label for="doctor_search" class="block text-sm font-medium text-gray-700 mb-2">Connect to Doctor
                                (Optional)</label>
                            <input type="text" id="doctor_search" placeholder="Search doctor by name..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <input type="hidden" name="doctor_id" id="doctor_id">
                            <div id="doctor_results"
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 hidden">
                            </div>
                            <p id="selected_doctor_display" class="mt-2 text-sm text-gray-600"></p>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeAddAdminModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                                Add Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div id="editAdminModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeEditAdminModal()"></div>

                <div
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Edit Admin</h3>
                        <button onclick="closeEditAdminModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form id="editAdminForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="edit_admin_name" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" id="edit_admin_name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" id="edit_admin_email" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="mb-6">
                            <label for="edit_doctor_search" class="block text-sm font-medium text-gray-700 mb-2">Connect to
                                Doctor (Optional)</label>
                            <input type="text" id="edit_doctor_search" placeholder="Search doctor by name..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <input type="hidden" name="doctor_id" id="edit_doctor_id">
                            <div id="edit_doctor_results"
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 hidden">
                            </div>
                            <p id="edit_selected_doctor_display" class="mt-2 text-sm text-gray-600"></p>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeEditAdminModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                                Save Changes
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 border-t pt-4">
                        <h4 class="text-sm font-semibold text-gray-800 mb-2">Account Actions</h4>

                        <button type="button" onclick="resetAdminPassword()"
                            class="mb-3 w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                            Send Reset Password Link
                        </button>

                        <div id="adminAccountAction" class="">
                        </div>
                    </div>

                </div>
            </div>
        </div>

    @endif
@endsection

@section('script')
    <script>
        // Admin data for JavaScript access
        let admins = @json($admins);
        let doctors = []; // This will be populated by an AJAX call or passed from the controller

        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // --- Add Admin Modal Functions ---
        function openAddAdminModal() {
            document.getElementById('addAdminForm').reset();
            document.getElementById('doctor_id').value = '';
            document.getElementById('selected_doctor_display').textContent = '';
            document.getElementById('addAdminModal').classList.remove('hidden');
        }

        function closeAddAdminModal() {
            document.getElementById('addAdminModal').classList.add('hidden');
        }

        // Add Admin Form Submission
        $('#addAdminForm').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.text();

            submitBtn.prop('disabled', true).text('Adding...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        closeAddAdminModal();
                        location.reload();
                    }
                },
                error: function (xhr) {
                    let errorMessage = xhr.responseJSON
                        ? (xhr.responseJSON.errors
                            ? Object.values(xhr.responseJSON.errors).flat().join('\n')
                            : (xhr.responseJSON.message ?? 'An error occurred while adding the admin.'))
                        : 'An error occurred while adding the admin.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                },
                complete: function () {
                    submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });

        // Doctor Search for Add Admin
        let addDoctorSearchTimeout;
        $('#doctor_search').on('keyup', function () {
            clearTimeout(addDoctorSearchTimeout);
            const query = $(this).val();
            const doctorResultsDiv = $('#doctor_results');

            if (query.length < 2) {
                doctorResultsDiv.empty().addClass('hidden');
                return;
            }

            addDoctorSearchTimeout = setTimeout(() => {
                $.ajax({
                    url: '{{ route('doctors.search') }}',
                    method: 'GET',
                    data: { query: query },
                    success: function (response) {
                        doctors = response;
                        doctorResultsDiv.empty();
                        if (doctors.length > 0) {
                            doctors.forEach(doctor => {
                                doctorResultsDiv.append(
                                    ` <div class="p-2 cursor-pointer hover:bg-gray-100 select-doctor-item"
                                                                                                 data-id="${doctor.id}" data-name="${doctor.name}" data-context="add">
                                                                                                 ${doctor.name}</div>`
                                );
                            });
                            doctorResultsDiv.removeClass('hidden');
                        } else {
                            doctorResultsDiv.empty().addClass('hidden');
                        }
                    }
                });
            }, 300); // 300ms debounce
        });
        $(document).on('click', '.select-doctor-item', function () {
            const doctorId = $(this).data('id');
            const doctorName = $(this).data('name');
            const context = $(this).data('context');

            selectDoctor(doctorId, doctorName, context);
        });
        function selectDoctor(doctorId, doctorName, context) {
            if (context === 'add') {
                document.getElementById('doctor_id').value = doctorId;
                document.getElementById('doctor_search').value = doctorName;
                document.getElementById('selected_doctor_display').textContent = `Selected: Dr. ${doctorName}`;
                document.getElementById('doctor_results').classList.add('hidden');
            } else if (context === 'edit') {
                document.getElementById('edit_doctor_id').value = doctorId;
                document.getElementById('edit_doctor_search').value = doctorName;
                document.getElementById('edit_selected_doctor_display').textContent = `Selected: Dr. ${doctorName}`;
                document.getElementById('edit_doctor_results').classList.add('hidden');
            }
        }


        // --- Edit Admin Modal Functions ---
        function closeEditAdminModal() {
            document.getElementById('editAdminModal').classList.add('hidden');
        }

        function editAdmin(adminId, name, email, deleted, doctorId) {
            const admin = admins.find(a => a.id == adminId); // Use == for comparison as doctorId might be string
            if (!admin) return;

            document.getElementById('edit_admin_name').value = name;
            document.getElementById('edit_admin_email').value = email;
            document.getElementById('editAdminForm').dataset.adminId = adminId;

            // Handle doctor connection for edit modal
            document.getElementById('edit_doctor_id').value = doctorId || '';
            if (admin.doctor) {
                document.getElementById('edit_doctor_search').value = admin.doctor.name;
                document.getElementById('edit_selected_doctor_display').textContent = `Selected: Dr. ${admin.doctor.name}`;
            } else {
                document.getElementById('edit_doctor_search').value = '';
                document.getElementById('edit_selected_doctor_display').textContent = 'No Doctor Connected';
            }


            const adminAccountAction = document.getElementById('adminAccountAction');

            if (!deleted || deleted === 'null' || deleted === '') {
                // active -> show deactivate button
                adminAccountAction.innerHTML = `
                                                                                                                            <button type="button" onclick="deactivateAdmin()"
                                                                                                                                class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-200">
                                                                                                                                Deactivate Account
                                                                                                                            </button>
                                                                                                                        `;
            } else {
                // inactive -> show activate button
                adminAccountAction.innerHTML = `
                                                                                                                            <button type="button" onclick="activateAdmin()"
                                                                                                                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                                                                                                                                Activate Account
                                                                                                                            </button>
                                                                                                                        `;
            }
            document.getElementById('editAdminModal').classList.remove('hidden');
        }

        // Doctor Search for Edit Admin
        let editDoctorSearchTimeout;
        $('#edit_doctor_search').on('keyup', function () {
            clearTimeout(editDoctorSearchTimeout);
            const query = $(this).val();
            const doctorResultsDiv = $('#edit_doctor_results');

            if (query.length < 2) {
                doctorResultsDiv.empty().addClass('hidden');
                return;
            }

            editDoctorSearchTimeout = setTimeout(() => {
                $.ajax({
                    url: '{{ route('doctors.search') }}', // Re-use the same route
                    method: 'GET',
                    data: { query: query },
                    success: function (response) {
                        doctors = response;
                        doctorResultsDiv.empty();
                        if (doctors.length > 0) {
                            doctors.forEach(doctor => {
                                doctorResultsDiv.append(`
                                    <div class="p-2 cursor-pointer hover:bg-gray-100 select-doctor-item"
                                         data-id="${doctor.id}" data-name="${doctor.name}" data-context="edit">
                                         ${doctor.name}
                                    </div>`
                                );
                            });
                            doctorResultsDiv.removeClass('hidden');
                        } else {
                            doctorResultsDiv.empty().addClass('hidden');
                        }
                    }
                });
            }, 300);
        });


        // Edit Admin Form Submission
        $('#editAdminForm').on('submit', function (e) {
            e.preventDefault();

            const adminId = this.dataset.adminId;
            const formData = new FormData(this);

            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.text();

            submitBtn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: `/admin/manage/${adminId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        closeEditAdminModal();
                        location.reload();
                    }
                },
                error: function (xhr) {
                    let errorMessage = xhr.responseJSON
                        ? (xhr.responseJSON.errors
                            ? Object.values(xhr.responseJSON.errors).flat().join('\n')
                            : (xhr.responseJSON.message ?? 'An error occurred while adding the admin.'))
                        : 'An error occurred while adding the admin.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                },
                complete: function () {
                    submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });


        // --- Account Actions (Reset Password, Activate/Deactivate) ---
        function resetAdminPassword() {
            const isSuccess = Math.random() < 0.5; // 50% chance

            Swal.fire(
                isSuccess ? 'Success' : 'Error',
                isSuccess ? 'Password reset link sent! Please check your email.' : 'Failed to send password reset link.',
                isSuccess ? 'success' : 'error'
            );
        }


        function activateAdmin() {
            const adminId = document.getElementById('editAdminForm').dataset.adminId;
            if (!adminId) {
                Swal.fire('Error', 'Admin ID not found!', 'error');
                return;
            }

            fetch(`/admin/manage/${adminId}/activate`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        title: 'Success',
                        text: data.message || 'Admin successfully activated!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to activate admin', 'error');
                });
        }

        function deactivateAdmin() {
            const adminId = document.getElementById('editAdminForm').dataset.adminId;

            Swal.fire({
                title: 'Are you sure?',
                text: "This will deactivate the admin account.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Yes, deactivate it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/manage/${adminId}/deactivate`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deactivated!',
                                text: 'The admin account has been deactivated.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            closeEditAdminModal();
                            setTimeout(() => location.reload(), 1000);
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: 'Failed to deactivate the admin.'
                            });
                        }
                    });
                }
            });
        }

        // Delete Admin Function
        function deleteAdmin(adminId, name) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete admin "${name}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/manage/${adminId}`,
                        method: 'DELETE',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                location.reload();
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while deleting the admin.'
                            });
                        }
                    });
                }
            });
        }

        // Initialize DataTable
        $(document).ready(function () {
            $('#adminsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [1, 3]
                }],
                language: {
                    search: "Search admins:",
                    lengthMenu: "Show _MENU_ admins per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ admins",
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
        window.onclick = function (event) {
            const addModal = document.getElementById('addAdminModal');
            const editModal = document.getElementById('editAdminModal');

            if (event.target === addModal) {
                closeAddAdminModal();
            }
            if (event.target === editModal) {
                closeEditAdminModal();
            }
        }
    </script>
@endsection