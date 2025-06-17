<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Panel')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    {{-- sweetalert cdn --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/tw-elements.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/css/tw-elements.min.css" />

    <script src="https://cdn.tailwindcss.com/3.3.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .swal2-confirm {
            background: #46c1a4 !important;
        }

        .swal2-deny,
        .swal2-cancel {
            background: #ec0143 !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Admin layout specific styles */
        .admin-content {
            transition: margin-left 0.3s ease-in-out;
        }

        @media (min-width: 1024px) {
            .admin-content {
                margin-left: 16rem; /* 64 * 0.25rem = 16rem */
            }
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: "class",-
            theme: {    
                fontFamily: {
                    sans: ["Roboto", "sans-serif"],
                    body: ["Roboto", "sans-serif"],
                    mono: ["ui-monospace", "monospace"],
                },
            },
            corePlugins: {
                preflight: false,
            },
        };
    </script>

    @yield('head')

</head>

<body class="bg-gray-50">
    
    <!-- Include Sidebar -->
    @include('admin.components.sidebar')

    <!-- Main Content Area -->
    <div class="admin-content min-h-screen">
        <!-- Top Navigation Bar -->
        <header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-6">
            <div class="flex items-center">
                <!-- Page Title -->
                <h2 class="text-xl font-semibold text-gray-800">
                    @yield('page-title', 'Dashboard')
                </h2>
            </div>

            <!-- Top Right Navigation -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition duration-200">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </button>

                <!-- User Profile Dropdown -->
                <div class="relative">
                    <button class="flex items-center space-x-2 p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition duration-200">
                        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <span class="hidden md:block font-medium">{{ session('name') ?? 'Admin' }}</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Breadcrumb -->
            @if(isset($breadcrumbs))
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    @foreach($breadcrumbs as $breadcrumb)
                        @if(!$loop->last)
                            <li>
                                <a href="{{ $breadcrumb['url'] }}" class="hover:text-indigo-600 transition duration-200">
                                    {{ $breadcrumb['title'] }}
                                </a>
                            </li>
                            <li><i class="fas fa-chevron-right text-xs"></i></li>
                        @else
                            <li class="text-gray-800 font-medium">{{ $breadcrumb['title'] }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
            @endif

            <!-- Main Content -->
            @yield('content')
        </main>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('error'))
        <script>
            Swal.fire({
                heightAuto: false,
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: "#3085d6",
            })
        </script>
    @endif

    @if (session()->has('success'))
        <script>
            Swal.fire({
                heightAuto: false,
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: "#3085d6",
            })
        </script>
    @endif

    @yield('script')

    <script src="https://cdn.jsdelivr.net/npm/tw-elements/js/tw-elements.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/tw-elements.umd.min.js"></script>

</body>

</html>
