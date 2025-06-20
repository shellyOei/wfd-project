<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hospital</title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- font inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue1: #4980ff; 
            --gray-inactive: #AAAAAA; 
            --gray1: #D9D9D9;
            --blueGradient1: #4DD7E2;
            --blueGradient2: #1618B9; 
            --yellowGradient1: #FBEB8C;
            --yellowGradient2: #F9863A;
            --background: #F4F4FD;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .swal2-confirm {
            background: #46c1a4 !important;
        }

        .swal2-deny,
        .swal2-cancel {
            background: #ec0143 !important;
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                fontFamily: {
                    sans: ["Open Sans", "sans-serif"],
                    body: ["Open Sans", "sans-serif"],
                    mono: ["ui-monospace", "monospace"],
                },
            },
            corePlugins: {
                preflight: false,
            },
        };
    </script>

    @yield('head')
    @stack('head')

</head>

<body class="bg-[var(--background)]">
    @include('partials.user-nav')

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

    <main class="w-screen overflow-x-hidden">
        @yield('content')
        @stack('content')
    </main>

    @include('partials.footer')
    
    @yield('script')
    @stack('script')

    @include('partials.add-patient-modal')

    <script src="https://cdn.jsdelivr.net/npm/tw-elements/js/tw-elements.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/tw-elements.umd.min.js"></script>

</body>

</html>
