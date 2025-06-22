@php

    $safeDate = \Carbon\Carbon::parse($date);
@endphp

@extends('admin.layout')

@section('title', 'Doctor Reservations')
@section('page-title', 'Doctor Reservations Schedule')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">View Doctor Reservations</h3>

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

        <form action="{{ route('admin.practice-schedules.index') }}" method="GET"
            class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700">Filter by Doctor</label>
                    <select name="doctor_id" id="doctor_id"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Doctors</option>
                        {{-- @php
                            dd($doctors);
                        @endphp --}}
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ $selectedDoctorId == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->front_title }} {{ $doctor->name }} {{ $doctor->back_title }}
                                ({{ $doctor->specialization->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="view_type" class="block text-sm font-medium text-gray-700">View Type</label>
                    <select name="view_type" id="view_type"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="daily" {{ $viewType == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ $viewType == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    </select>
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Select Date/Week</label>
                    <input type="date" name="date" id="date" value="{{ $safeDate->format('Y-m-d') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.practice-schedules.index') }}"
                        class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        @if ($viewType == 'daily')
            <h4 class="text-xl font-bold text-gray-800 mb-4">Daily Schedule for {{ $safeDate->format('l, M d, Y') }}</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Doctor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time
                                Slot</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reservation Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $todaySchedules = $groupedSchedules[$safeDate->format('Y-m-d')] ?? collect();
                        // @dump($todaySchedules) 

                        @endphp
                        @forelse ($todaySchedules->sortBy('Datetime') as $schedule)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $schedule->doctor->front_title }} {{ $schedule->doctor->name }}
                                    {{ $schedule->doctor->back_title }} ({{ $schedule->doctor->specialization->name }})
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @php
                                        $startTime = \Carbon\Carbon::parse($schedule->Datetime);
                                        $endTime = $startTime->copy()->addMinutes(30);
                                    @endphp
                                    {{ $startTime->format('H:i A') }} - {{ $endTime->format('H:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if ($schedule->appointments->count() > 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Booked
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Available
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <form action="{{ route('admin.practice-schedules.destroy', $schedule->id) }}"
                                        method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition duration-200"
                                            title="Delete Slot">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 px-2 text-center text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-medium">No practice schedules found for this date.</p>
                                    <p class="text-sm">Generate practice schedules from master availability.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @elseif ($viewType == 'weekly')
            <h4 class="text-xl font-bold text-gray-800 mb-4">Weekly Schedule for
                {{ $safeDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->format('M d, Y') }} -
                {{ $safeDate->copy()->endOfWeek(\Carbon\Carbon::SUNDAY)->format('M d, Y') }}</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @php
                    $startOfWeek = $safeDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                @endphp
                @for ($i = 0; $i < 7; $i++)
                    @php
                        $currentDay = $startOfWeek->copy()->addDays($i);
                        $dayKey = $currentDay->format('Y-m-d');
                        $daySchedules = $weeklySchedule[$dayKey] ?? collect();
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                        <h5 class="text-md font-semibold text-gray-900 mb-3">{{ $currentDay->format('l, M d') }}</h5>
                        @forelse($daySchedules->sortBy('Datetime') as $schedule)
                            <div class="border-b border-gray-100 py-2 last:border-b-0">
                                <p class="text-sm font-medium text-gray-900">{{ $schedule->doctor->front_title }}
                                    {{ $schedule->doctor->name }} {{ $schedule->doctor->back_title }}</p>
                                <p class="text-xs text-gray-600">{{ $schedule->doctor->specialization->name }}</p>
                                {{-- Added ->name here --}}
                                <p class="text-sm text-gray-700 mt-1">
                                    <i class="fas fa-clock mr-1 text-indigo-500"></i>
                                    {{-- Calculate and display the 30-minute range --}}
                                    @php
                                        $startTime = \Carbon\Carbon::parse($schedule->Datetime);
                                        $endTime = $startTime->copy()->addMinutes(30);
                                    @endphp
                                    {{ $startTime->format('H:i A') }} - {{ $endTime->format('H:i A') }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    @if ($schedule->appointments->count() > 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Booked ({{ $schedule->appointments->count() }})
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Available
                                        </span>
                                    @endif
                                </p>
                                <form action="{{ route('admin.practice-schedules.destroy', $schedule->id) }}"
                                    method="POST" class="delete-form mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete Slot
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">No practice schedules for this day.</p>
                        @endforelse
                    </div>
                @endfor
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteForms = document.querySelectorAll('.delete-form');

                deleteForms.forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault(); // Prevent the default form submission

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this! This will also delete any linked appointments!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit(); // Submit the form if confirmed
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
