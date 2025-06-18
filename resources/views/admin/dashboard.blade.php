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
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar-check text-blue-600"></i>
                    <span class="text-sm text-gray-600">Last 30 days</span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="appointmentsChart"></canvas>
            </div>
        </div>

        <!-- Patients by Department -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Patients by Department</h3>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-chart-pie text-green-600"></i>
                    <span class="text-sm text-gray-600">All time</span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="patientsByDepartmentChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Monthly Revenue</h3>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-chart-bar text-purple-600"></i>
                    <span class="text-sm text-gray-600">Last 6 months</span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Patient Registration Trends -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Patient Registrations</h3>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-chart-line text-orange-600"></i>
                    <span class="text-sm text-gray-600">Last 6 months</span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="patientRegistrationChart"></canvas>
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
                                    {{ $appointment->schedule->dayAvailable->doctor->front_title }} {{ $appointment->schedule->dayAvailable->doctor->name }} {{ $appointment->schedule->dayAvailable->doctor->back_title }}
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

@section('script')
<script>
    // Chart.js configuration
    Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
    Chart.defaults.color = '#6B7280';

    // 1. Appointments Overview Chart (Line Chart)
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    const appointmentsChart = new Chart(appointmentsCtx, {
        type: 'line',
        data: {
            labels: @json($appointmentsChartData['labels']),
            datasets: [{
                label: 'Appointments',
                data: @json($appointmentsChartData['data']),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#3B82F6',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: '#3B82F6'
                }
            }
        }
    });

    // 2. Patients by Department Chart (Doughnut Chart)
    const patientsByDepartmentCtx = document.getElementById('patientsByDepartmentChart').getContext('2d');
    const patientsByDepartmentChart = new Chart(patientsByDepartmentCtx, {
        type: 'doughnut',
        data: {
            labels: @json($patientsByDepartmentData['labels']),
            datasets: [{
                data: @json($patientsByDepartmentData['data']),
                backgroundColor: @json($patientsByDepartmentData['colors']),
                borderWidth: 0,
                hoverBorderWidth: 2,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#10B981',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' patients (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // 3. Revenue Chart (Bar Chart)
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json($revenueChartData['labels']),
            datasets: [{
                label: 'Revenue (Rp)',
                data: @json($revenueChartData['data']),
                backgroundColor: 'rgba(139, 92, 246, 0.8)',
                borderColor: '#8B5CF6',
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#8B5CF6',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                notation: 'compact',
                                compactDisplay: 'short'
                            }).format(value);
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // 4. Patient Registration Chart (Line Chart)
    const patientRegistrationCtx = document.getElementById('patientRegistrationChart').getContext('2d');
    const patientRegistrationChart = new Chart(patientRegistrationCtx, {
        type: 'line',
        data: {
            labels: @json($patientRegistrationData['labels']),
            datasets: [{
                label: 'New Patients',
                data: @json($patientRegistrationData['data']),
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#F59E0B',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#F59E0B',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Add animation and interactivity
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to chart containers
        const chartContainers = document.querySelectorAll('.bg-white.rounded-xl');
        chartContainers.forEach(container => {
            container.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
                this.style.transition = 'all 0.3s ease';
            });

            container.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
            });
        });
    });
</script>
@endsection