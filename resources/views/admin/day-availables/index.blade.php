@extends('admin.layout')

@section('title', 'Manage Doctor Availability')
@section('page-title', 'Doctor Availability Management')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Doctor Availabilityyy (Master Schedules)</h3>
            <a href="{{ route('admin.day-availables.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i> Add New Availability
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

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.day-availables.index') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700">Filter by Doctor</label>
                    <select name="doctor_id" id="doctor_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ $selectedDoctorId == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->front_title }} {{ $doctor->name }} {{ $doctor->back_title }} ({{ $doctor->specialization->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="day" class="block text-sm font-medium text-gray-700">Filter by Day</label>
                    <select name="day" id="day" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Days</option>
                        @foreach($daysOfWeek as $dayOption)
                            <option value="{{ $dayOption }}" {{ $selectedDay == $dayOption ? 'selected' : '' }}>
                                {{ $dayOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.day-availables.index') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Slot</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($dayAvailables as $dayAvailable)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $dayAvailable->doctor->front_title }} {{ $dayAvailable->doctor->name }} {{ $dayAvailable->doctor->back_title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dayAvailable->day }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($dayAvailable->start_time)->format('H:i A') }} -
                                {{ \Carbon\Carbon::parse($dayAvailable->end_time)->format('H:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.day-availables.edit', $dayAvailable->id) }}" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition duration-200" title="Edit Availability">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    {{-- The delete form with SweetAlert integration --}}
                                    <form action="{{ route('admin.day-availables.destroy', $dayAvailable->id) }}" method="POST" class="delete-form-availability">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition duration-200" title="Delete Availability">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 px-2 text-center text-gray-500">
                                <i class="fas fa-calendar-week text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">No day availabilities found</p>
                                <p class="text-sm">Define recurring availability for doctors here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $dayAvailables->links() }}
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Select all forms that have the class 'delete-form-availability'
                const deleteForms = document.querySelectorAll('.delete-form-availability');

                // Loop through each delete form and attach the event listener
                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (event) {
                        event.preventDefault(); // Stop the default form submission

                        // Get details for the SweetAlert message
                        const row = this.closest('tr');
                        const doctorName = row.querySelector('td:nth-child(1)').textContent.trim();
                        const day = row.querySelector('td:nth-child(2)').textContent.trim();
                        const timeSlot = row.querySelector('td:nth-child(3)').textContent.trim();

                        Swal.fire({
                            title: 'Are you sure?',
                            text: `You are about to delete the availability for ${doctorName} on ${day} from ${timeSlot}. This action cannot be undone! Deleting a master availability record will NOT automatically delete existing generated practice schedules or appointments, but it will prevent future schedule generation based on this availability.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d', // Changed to a more neutral gray for consistency
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // If the user confirms, submit the form
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection