<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-indigo-900 to-purple-900 shadow-2xl transform transition-transform duration-300 ease-in-out lg:translate-x-0"
    id="sidebar">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 bg-black/20">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-hospital text-indigo-600 text-lg"></i>
            </div>
            <h1 class="text-xl font-bold text-white">Admin</h1>
        </div>
        <!-- Mobile close button -->
        <button class="lg:hidden text-white hover:text-gray-300" onclick="toggleSidebar()">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-8 px-4">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') ?? '#' }}"
                    class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 border-r-4 border-white' : '' }}">
                    <i class="fas fa-tachometer-alt text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <!-- Doctors -->
            <li>
                <a href="{{ route('admin.doctors.index') }}"
                    class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition duration-200 group {{ request()->routeIs('admin.doctors*') ? 'bg-white/20 border-r-4 border-white' : '' }}">
                    <i class="fas fa-user-md text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="foPnt-medium">Doctors</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.day-availables.index') }}"
                    class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition duration-200 group {{ request()->routeIs('admin.day-availables*') ? 'bg-white/20 border-r-4 border-white' : '' }}">
                    <i class="fas fa-calendar-week text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium">Doctor Availability</span>
                </a>
            </li>

            {{-- <li>
                <a href="{{ route('admin.practice-schedules.index') }}"
                   class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition duration-200 group {{ request()->routeIs('admin.practice-schedules*') ? 'bg-white/20 border-r-4 border-white' : '' }}">
                    <i class="fas fa-clipboard-list text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium">Reservations & Slots</span>
                </a>
            </li> --}}

            <!-- Appointments -->
            <li>
                <a href="{{ route('admin.practice-schedules.index') }}"
                    class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition duration-200 group {{ request()->routeIs('admin.appointments*') ? 'bg-white/20 border-r-4 border-white' : '' }}">
                    <i class="fas fa-calendar-check text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium">Appointments</span>
                </a>
            </li>

            <!-- Patients -->
            <li>
                <a href="{{ route('admin.patients') ?? '#' }}"
                    class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition duration-200 group {{ request()->routeIs('admin.patients*') ? 'bg-white/20 border-r-4 border-white' : '' }}">
                    <i class="fas fa-users text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium">Patients</span>
                </a>
            </li>



            <!-- Reports -->
            <li>
                <a href=""
                    class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition duration-200 group {{ request()->routeIs('admin.reports*') ? 'bg-white/20 border-r-4 border-white' : '' }}">
                    <i class="fas fa-chart-bar text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium">Reports</span>
                </a>
            </li>
        </ul>

        <!-- Divider -->
        <div class="my-8 border-t border-white/20"></div>

        <!-- Settings & Logout -->
        <ul class="space-y-2">
            @if(session('doctor_id') == NULL)
            <!-- Users -->
            <li>
                <a href="{{ route('admin.users') }}"
                   class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition duration-200 group {{ request()->routeIs('admin.appointments*') ? 'bg-white/20 border-r-4 border-white' : '' }}">
                    <i class="fas fa-users-gear text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium">Users</span>
                </a>
            </li>

            
            <!-- Admins -->
            <li>
                <a href="{{ route('admin.manage') }}"
                   class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition duration-200 group {{ request()->routeIs('admin.appointments*') ? 'bg-white/20 border-r-4 border-white' : '' }}">
                    <i class="fas fa-user-tie text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium">Admins</span>
                </a>
            </li>
            @endif

            <!-- Settings -->
            <li>
                <a href=""
                    class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition duration-200 group">
                    <i class="fas fa-cog text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                    <span class="font-medium">Settings</span>
                </a>
            </li>

            <!-- Logout -->
            <li>
                <form action="{{ route('admin.logout') ?? '#' }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-3 text-white rounded-lg hover:bg-red-500/20 transition duration-200 group">
                        <i class="fas fa-sign-out-alt text-lg mr-3 group-hover:scale-110 transition duration-200"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- User Info at Bottom -->
    <div class="absolute bottom-0 left-0 right-0 p-4 bg-black/20">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-user text-indigo-600"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">
                    {{ session('name') ?? 'Admin User' }}
                </p>
                <p class="text-xs text-white/70 truncate">
                    {{ session('email') ?? 'admin@example.com' }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Sidebar Overlay -->
<div class="fixed inset-0 z-40 bg-black/50 lg:hidden hidden" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Mobile Menu Button -->
<button
    class="fixed top-4 left-4 z-50 lg:hidden bg-indigo-600 text-white p-3 rounded-lg shadow-lg hover:bg-indigo-700 transition duration-200"
    onclick="toggleSidebar()">
    <i class="fas fa-bars text-lg"></i>
</button>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const menuButton = event.target.closest('button');

        if (window.innerWidth < 1024 && !sidebar.contains(event.target) && !menuButton) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
        }
    });

    // Initialize sidebar state on page load
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
        }
    });
</script>
