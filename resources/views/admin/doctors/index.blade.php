@extends('admin.layout')

@section('title', 'Manage Doctors')
@section('page-title', 'Doctors Managementt')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">All Doctorss</h3>
            <a href="{{ route('admin.doctors.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i> Add New Doctor
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Search and Filter Controls --}}
        <div class="mb-6 flex flex-col md:flex-row items-center gap-4">
            <div class="w-full md:w-auto flex-grow flex items-center gap-2">
                <input type="text" id="search-input" name="search" placeholder="Search by name, specialization, or address..."
                        value="{{ request('search') }}"
                        class="flex-grow rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <select id="specialization-filter" name="specialization_id" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Specializations</option>
                    @foreach ($specializations as $spec)
                        <option value="{{ $spec->id }}" {{ request('specialization_id') == $spec->id ? 'selected' : '' }}>
                            {{ $spec->name }}
                        </option>
                    @endforeach
                </select>
                <button type="button" id="apply-filter-button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                @if (request('search') || request('specialization_id'))
                    <a href="{{ route('admin.doctors.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-times mr-2"></i> Clear
                    </a>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto relative">
            {{-- Loading spinner --}}
            <div id="loading-spinner" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 z-10 hidden">
                <div class="fas fa-spinner fa-spin text-4xl text-indigo-600"></div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th> Added Photo Header --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th> {{-- Added Description Header --}}
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="doctors-table-body" class="bg-white divide-y divide-gray-200">
                    {{-- Initial rows loaded directly from server --}}
                    @forelse ($doctors as $doctor)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $doctor->doctor_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $doctor->front_title }} {{ $doctor->name }} {{ $doctor->back_title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $doctor->specialization->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $doctor->address ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $doctor->phone ?? '-' }}</td>
                            {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                @if ($doctor->photo)
                                    <img src="{{ $doctor->photo }}" alt="Doctor Photo" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    -
                                @endif
                            </td> --}}
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs overflow-hidden text-ellipsis whitespace-nowrap" title="{{ $doctor->description }}">
                                {{ $doctor->description ?? '-' }}
                            </td> {{-- Added Description Data --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition duration-200" title="Edit Doctor">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    {{-- Modified delete form for SweetAlert --}}
                                    <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition duration-200" title="Delete Doctor">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 px-2 text-center text-gray-500"> {{-- colspan adjusted from 6 to 8 --}}
                                <i class="fas fa-user-md text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">No doctors found</p>
                                <p class="text-sm">Start by adding a new doctor, or adjust your search criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="doctors-pagination" class="mt-4">
            {{-- Initial pagination links loaded directly from server --}}
            {{ $doctors->links() }}
        </div>
    </div>

    {{-- JavaScript for Dynamic Search, Pagination, and SweetAlert --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-input');
            const specializationFilter = document.getElementById('specialization-filter');
            const applyFilterButton = document.getElementById('apply-filter-button');
            const doctorsTableBody = document.getElementById('doctors-table-body');
            const doctorsPagination = document.getElementById('doctors-pagination');
            const loadingSpinner = document.getElementById('loading-spinner');

            let searchTimeout = null;

            function showSpinner() {
                loadingSpinner.classList.remove('hidden');
            }

            function hideSpinner() {
                loadingSpinner.classList.add('hidden');
            }

            function fetchDoctors(page = 1) {
                showSpinner();

                const searchValue = searchInput.value;
                const specializationId = specializationFilter.value;

                // Construct URL with query parameters
                const url = new URL("{{ route('admin.doctors.index') }}");
                url.searchParams.append('page', page);
                if (searchValue) {
                    url.searchParams.append('search', searchValue);
                }
                if (specializationId) {
                    url.searchParams.append('specialization_id', specializationId);
                }

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Important: Identify as AJAX request
                        'Accept': 'application/json' // Request JSON response
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Generate table rows HTML from JSON data
                    let tableRowsHtml = '';
                    if (data.doctors.length > 0) {
                        data.doctors.forEach(doctor => {
                            const frontTitle = doctor.front_title ? doctor.front_title + ' ' : '';
                            const backTitle = doctor.back_title ? ' ' + doctor.back_title : '';
                            const specializationName = doctor.specialization ? doctor.specialization.name : 'N/A';
                            const address = doctor.address || '-';
                            const phone = doctor.phone || '-';
                            const doctorNumber = doctor.doctor_number || '-';
                            const photoHtml = doctor.photo ? `<img src="${doctor.photo}" alt="Doctor Photo" class="h-10 w-10 rounded-full object-cover">` : '-';
                            const description = doctor.description || '-';

                            // Dynamically generate the delete form, including CSRF token and method spoofing
                            // Add 'delete-form' class to the form
                            const deleteFormHtml = `
                                <form action="/admin/doctors/${doctor.id}" method="POST" class="delete-form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition duration-200" title="Delete Doctor">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            `;

                            tableRowsHtml += `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${doctorNumber}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${frontTitle}${doctor.name}${backTitle}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${specializationName}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${address}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${phone}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${photoHtml}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs overflow-hidden text-ellipsis whitespace-nowrap" title="${doctor.description}">${description}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="/admin/doctors/${doctor.id}/edit" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition duration-200" title="Edit Doctor">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            ${deleteFormHtml}
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        tableRowsHtml = `
                            <tr>
                                <td colspan="8" class="py-8 px-2 text-center text-gray-500">
                                    <i class="fas fa-user-md text-4xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-medium">No doctors found</p>
                                    <p class="text-sm">Start by adding a new doctor, or adjust your search criteria.</p>
                                </td>
                            </tr>
                        `;
                    }

                    doctorsTableBody.innerHTML = tableRowsHtml;
                    doctorsPagination.innerHTML = data.pagination;
                    hideSpinner();

                    // Re-attach event listeners to new pagination links and delete buttons
                    attachPaginationListeners();
                    attachDeleteListeners();
                })
                .catch(error => {
                    console.error('Error fetching doctors:', error);
                    hideSpinner();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to load doctors. Please try again later.',
                    });
                });
            }

            // Function to attach click listeners to new pagination links
            function attachPaginationListeners() {
                doctorsPagination.querySelectorAll('.pagination a').forEach(link => {
                    link.removeEventListener('click', handlePaginationClick);
                    link.addEventListener('click', handlePaginationClick);
                });
            }

            // Handler for pagination link clicks
            function handlePaginationClick(e) {
                e.preventDefault();
                const pageUrl = new URL(e.target.href);
                const page = pageUrl.searchParams.get('page');
                fetchDoctors(page);
            }

            // Function to attach SweetAlert to delete forms
            function attachDeleteListeners() {
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.removeEventListener('submit', handleDeleteSubmit); // Prevent duplicate listeners
                    form.addEventListener('submit', handleDeleteSubmit);
                });
            }

            function handleDeleteSubmit(e) {
                e.preventDefault(); // Prevent the default form submission

                const form = e.target;
                const doctorName = form.closest('tr').querySelector('td:nth-child(2)').textContent.trim(); // Get doctor name from table row

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete ${doctorName}. This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, submit the form
                        form.submit();
                    }
                });
            }

            // Event listeners for search input and specialization filter
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    fetchDoctors(1);
                }, 400);
            });

            specializationFilter.addEventListener('change', function () {
                fetchDoctors(1);
            });

            applyFilterButton.addEventListener('click', function() {
                fetchDoctors(1);
            });

            // Initial attachment of pagination and delete listeners (for first page load)
            attachPaginationListeners();
            attachDeleteListeners();
        });
    </script>
@endsection