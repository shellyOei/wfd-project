@extends('admin.layout')

@section('title', 'Users')
@section('page-title', 'Users Management')

@section('content')
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
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Users</h1>
                <p class="text-gray-600 mt-1">Manage user information and medical records</p>
            </div>
            <div class="flex space-x-3">
                <!-- <button onclick="openAddModal()"
                                                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                                                                                    <i class="fas fa-plus mr-2"></i>Add New User
                                                                                </button> -->
                <button
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>
    </div>
    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="p-3 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">All Users ({{ $users->count() }})</h3>
        </div>
        <div class="overflow-x-auto p-6">
            <table class="w-full py-6 !my-6" id="usersTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-center py-4 px-6 font-medium text-gray-700">User</th>
                        <th class="text-center py-4 px-6 font-medium text-gray-700">Connected Patients</th>
                        <th class="text-center py-4 px-6 font-medium text-gray-700">Status</th>
                        <th class="text-center py-4 px-6 font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <!-- User Info -->
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-user text-indigo-600"></i>
                                    </div>
                                    <p class="font-semibold text-gray-900">{{ $user->email }}</p>
                                </div>
                            </td>

                            <!-- View Connected Patients -->
                            <td class="py-4 px-6 text-center">
                                <button
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-sm font-medium transition duration-200 flex items-center mx-auto"
                                    onclick="viewPatients('{{ $user->id }}', '{{ $user->email }}')">
                                    <i class="fas fa-file-medical mr-2"></i>View ({{ $user->patients->count()}})
                                </button>
                            </td>


                            <!-- Status -->
                            <td class="py-4 px-6 text-center justify-items-center items-center">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-medium  {{ is_null($user->deleted_at) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ is_null($user->deleted_at) ? 'Active' : 'Inactive' }}
                                </span>

                            </td>
                            <!-- Actions -->
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- <button class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition duration-200"
                                                                                                                                            title="Reset Password" onclick="viewUser('{{ $user->id }}')">
                                                                                                                                            <i class="fas fa-key text-sm"></i>
                                                                                                                                        </button> -->
                                    <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition duration-200"
                                        title="Edit User"
                                        onclick="editUser('{{ $user->id }}', '{{ $user->email }}', '{{ $user->deleted_at }}')">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition duration-200"
                                        title="Delete User" onclick="deleteUser('{{ $user->id }}', '{{ $user->email }}')">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-500">No users found</p>
                                <p class="text-gray-400">Start by adding your first user</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeEditModal()"></div>

            <div
                class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Edit User Email</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Form to change email -->
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="edit_email" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                            Save Email
                        </button>
                    </div>
                </form>


                <div class="mt-6 border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-2">Account Actions</h4>

                    <!-- Reset Password -->
                    <button type="button" onclick="resetPassword()"
                        class="mb-3 w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                        Send Reset Password Link
                    </button>

                    <div id="accountAction" class="">
                        <!-- Tombol Activate / Deactivate -->
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- view Patients Modal -->
    <div id="viewPatientsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeViewPatientsModal()">
            </div>

            <div
                class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900"><span id="userNameHistory"></span>
                    </h3>
                    <button onclick="closeViewPatientsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div id="viewPatientsContent">
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Loading connected patients...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // User data for JavaScript access
        let users = @json($users);

        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        function resetPassword() {
            const userId = document.getElementById('editUserModal').dataset.userId;

            fetch(`/admin/users/${userId}/reset-password`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
                .then(response => {
                    if (response.ok) {
                        alert('Password reset link sent!');
                    } else {
                        alert('Failed to send password reset link.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Error occurred.');
                });
        }

        function activateUser() {
            const userId = document.getElementById('editUserForm').dataset.userId;
            if (!userId) {
                Swal.fire('Error', 'User ID tidak ditemukan!', 'error');
                return;
            }

            fetch(`/admin/users/${userId}/activate`, {
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
                    Swal.fire('Success', data.message || 'User berhasil diaktifkan', 'success');
                    // refresh halaman setelah 1 detik supaya update status muncul
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Gagal mengaktifkan user', 'error');
                });
        }

        function deactivateUser() {
            const userId = document.getElementById('editUserForm').dataset.userId;

            Swal.fire({
                title: 'Are you sure?',
                text: "This will deactivate the user account.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Yes, deactivate it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/users/${userId}/deactivate`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deactivated!',
                                text: 'The user account has been deactivated.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            closeEditModal();
                            location.reload();
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: 'Failed to deactivate the user.'
                            });
                        }
                    });
                }
            });
        }


        function closeEditModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }
        function editUser(userId, email, deleted) {
            const user = users.find(p => p.id === userId);
            if (!user) return;
            document.getElementById('edit_email').value = email;
            const accountAction = document.getElementById('accountAction');

            // Cek deleted sebagai string, kosong, atau 'null'
            if (!deleted || deleted === 'null' || deleted === '') {
                // aktif -> tampilkan tombol deactivate
                accountAction.innerHTML = `
                            <button type="button" onclick="deactivateUser()"
                                class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-200">
                                Deactivate Account
                            </button>
                        `;
            } else {
                // nonaktif -> tampilkan tombol activate
                accountAction.innerHTML = `
                            <button type="button" onclick="activateUser()"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                                Activate Account
                            </button>
                        `;
            }
            document.getElementById('editUserModal').classList.remove('hidden');
            document.getElementById('editUserForm').dataset.userId = userId;

        }


        // Edit User Form Submission
        $('#editUserForm').on('submit', function (e) {
            e.preventDefault();

            const userId = this.dataset.userId;
            const formData = new FormData(this);

            // console.log(formData);
            formData.append('_method', 'PUT');

            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.text();

            // Show loading state
            submitBtn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: `/admin/users/${userId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        closeEditModal();
                        location.reload();
                    }
                },
                error: function (xhr) {
                    let errorMessage = 'An error occurred while updating the user.';

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).flat().join('\n');
                    }

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

        // Delete User Function
        function deleteUser(userId, email) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete user "${email}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/users/${userId}`,
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

                                location.reload(); // Reload to update the table
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while deleting the user.'
                            });
                        }
                    });
                }
            });
        }



        // View Patients Modal Functions
        function viewPatients(userId, email) {
            document.getElementById('userNameHistory').textContent = email;
            document.getElementById('viewPatientsModal').classList.remove('hidden');

            setTimeout(() => {
                const user = users.find(p => p.id === userId);
                if (!user) {
                    document.getElementById('viewPatientsContent').innerHTML = `<p class="text-red-500">User not found</p>`;
                    return;
                }

                const patients = user.profiles?.map(profile => profile.patient) || [];

                document.getElementById('viewPatientsContent').innerHTML = `
                                                                            <div>
                                                                                <h4 class="font-semibold text-gray-900 mb-3">Connected Patients</h4>
                                                                                ${patients.length > 0 ? `
                                                                                    <ul class="list-disc list-inside space-y-1 max-h-60 overflow-y-auto border border-gray-200 rounded p-3">
                                                                                        ${patients.map(patient => `
                                                                                            <li><strong>${patient.name || 'Unnamed Patient'}</strong> (ID: ${patient.id})</li>
                                                                                        `).join('')}
                                                                                    </ul>
                                                                                ` : `
                                                                                    <p class="text-gray-500 italic">No patients connected to this user.</p>
                                                                                `}
                                                                            </div>
                                                                        `;
            }, 500);
        }



        function closeViewPatientsModal() {
            document.getElementById('viewPatientsModal').classList.add('hidden');
        }

        // Initialize DataTable
        $(document).ready(function () {
            $('#usersTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [1, 3] // Corrected from [3, 5]
                }],
                language: {
                    search: "Search users:",
                    lengthMenu: "Show _MENU_ users per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ users",
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
            const editModal = document.getElementById('editUserModal');
            const historyModal = document.getElementById('viewPatientsModal');

            if (event.target === editModal) {
                closeEditModal();
            }
            if (event.target === historyModal) {
                closeViewPatientsModal();
            }
        }
    </script>
@endsection