@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Dashboard Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Doctors -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Doctors</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalDoctors }}</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <i class="fas fa-stethoscope mr-1"></i>Active doctors
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-md text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Patients -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalPatients) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-user-plus mr-1"></i>Registered patients
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Appointments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $todayAppointments }}</p>
                    <p class="text-sm text-orange-600 mt-1">
                        <i class="fas fa-clock mr-1"></i>{{ $pendingAppointments }} pending
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-chart-line mr-1"></i>This month
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Appointments Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Appointments Overview</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <p class="text-gray-500">Chart will be displayed here</p>
            </div>
        </div>
        {{-- Patients by Department --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Patients by Department</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <p class="text-gray-500">Chart will be displayed here</p>
            </div>
        </div>

    </div>
    <div class="gap-6 mb-8 flex flex-col">
        <!-- Recent Appointments -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Appointments</h3>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View all</a>
            </div>
    
            <!-- Appointments Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-2 font-medium text-gray-700">Patient Name</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-700">Doctor</th>
                            <th class="text-left py-3 px-2 font-medium text-gray-700">Date</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentAppointments as $index => $appointment)
                            @php
                                $colors = ['blue', 'pink', 'green', 'purple', 'orange'];
                                $color = $colors[$index % count($colors)];
                            @endphp
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="py-4 px-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-{{ $color }}-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-{{ $color }}-600 text-sm"></i>
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $appointment->patient->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-2 text-gray-700">
                                    {{ $appointment->schedule->doctor->front_title }} {{ $appointment->schedule->doctor->name }} {{ $appointment->schedule->doctor->back_title }}
                                </td>
                                <td class="py-4 px-2 text-gray-700">
                                    {{ \Carbon\Carbon::parse($appointment->schedule->Datetime)->format('M d, Y - g:i A') }}
                                </td>
                                <td class="py-4 px-2">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition duration-200" title="View Details">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                        <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition duration-200" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition duration-200" title="Cancel">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 px-2 text-center text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-medium">No appointments found</p>
                                    <p class="text-sm">Recent appointments will appear here</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200 group">
                    <i class="fas fa-user-plus text-blue-600 text-xl mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium text-blue-700">Add Doctor</span>
                </button>
    
                <button class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200 group">
                    <i class="fas fa-users text-green-600 text-xl mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium text-green-700">Add Patient</span>
                </button>
    
                <button class="flex items-center justify-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition duration-200 group">
                    <i class="fas fa-calendar-plus text-orange-600 text-xl mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium text-orange-700">Schedule Appointment</span>
                </button>
    
                <button class="flex items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition duration-200 group">
                    <i class="fas fa-file-alt text-purple-600 text-xl mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium text-purple-700">Generate Report</span>
                </button>
            </div>
        </div>
    </div>
@endsection