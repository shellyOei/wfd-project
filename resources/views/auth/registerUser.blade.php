@extends('layout')

@section('content')
    <div class="flex flex-col h-screen w-screen overflow-x-hidden items-center pt-5 pb-20 text-black">
    {{-- logo --}}
        <img class="w-[35%]" src="{{ asset('assets/ewaps-logo.png')}}" alt="">

        <form class="w-[85%] space-y-4" action="/register" method="POST">
            @csrf 
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">There were some problems with your input.</span>
                    <ul class="mt-3 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Name Input Field --}}
            <div class="form-group w-full max-w-md mx-auto flex flex-col space-y-1">
                <label class="font-semibold text-md" for="name">Nama</label>
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Contoh: Endawati"
                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent"
                        name="name" id="name" value="{{ old('name') }}" required>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-gray-400"></i>
                    </div>
                </div>
            </div>

            {{-- Email Input Field --}}
            <div class="form-group w-full max-w-md mx-auto flex flex-col space-y-1">
                <label class="font-semibold text-md" for="email">Email</label>
                <div class="relative">
                    <input
                        type="email"
                        placeholder="Contoh: endawati@example.com"
                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent"
                        name="email" id="email" value="{{ old('email') }}" required>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-gray-400"></i>
                    </div>
                </div>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone Number Input Field --}}
            <div class="form-group w-full max-w-md mx-auto flex flex-col space-y-1">
                <label class="font-semibold text-md" for="phone">Nomor Telepon</label>
                <div class="relative">
                    <input
                        type="tel"
                        placeholder="Contoh: 081234567890"
                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent"
                        name="phone" id="phone" value="{{ old('phone') }}" required>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-phone text-gray-400"></i>
                    </div>
                </div>
            </div>

            {{-- Password Input Field --}}
            <div class="form-group w-full max-w-md mx-auto flex flex-col space-y-1">
                <label class="font-semibold text-md" for="password">Password</label>
                <div class="relative">
                    <input
                        type="password"
                        placeholder="Minimal 8 karakter"
                        class="w-full pl-4 pr-10 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent"
                        name="password" id="password" required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" id="togglePassword">
                        <i class="fa-solid fa-eye-slash text-gray-400"></i>
                    </div>
                </div>
            </div>

            {{-- Confirm Password Input Field --}}
            <div class="form-group w-full max-w-md mx-auto flex flex-col space-y-1">
                <label class="font-semibold text-md" for="password_confirmation">Konfirmasi Password</label>
                <div class="relative">
                    <input
                        type="password"
                        placeholder="Ulangi password Anda"
                        class="w-full pl-4 pr-10 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent"
                        name="password_confirmation" id="password_confirmation" required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" id="toggleConfirmPassword">
                        <i class="fa-solid fa-eye-slash text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="form-group w-full max-w-md mx-auto flex items-start space-x-2">
                <input
                    type="checkbox"
                    name="terms"
                    id="terms"
                    class="mt-1 w-4 h-4 rounded border-[var(--blue1)] text-[var(--blue1)] focus:ring-[var(--blue1)] @error('terms') border-red-500 @enderror"
                    {{ old('terms') ? 'checked' : '' }} {{-- Retain state --}}
                    required>
                <label for="terms" class="text-gray-700">
                    Saya menyetujui
                    <a href="/terms-of-service" class="text-[var(--blue1)] underline hover:text-blue-700">Syarat Layanan</a>
                    dan
                    <a href="/privacy-policy" class="text-[var(--blue1)] underline hover:text-blue-700">Kebijakan Privasi</a>
                </label>
            </div>
           
            <button type="submit" class="btn text-center w-full py-3 rounded-lg text-white font-semibold shadow-lg
                bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)]
                hover:from-purple-600 hover:to-indigo-700
                hover:shadow-xl transition-all duration-300 ease-in-out
                focus:outline-none focus:ring-2 focus:ring-[var(--blueGradient1)] focus:ring-opacity-75 font-bold tracking-wide">
                Buat Akun
            </button>
        </form>

        <p class="mt-5">Sudah punya akun? <a href="/login" class="text-[var(--blue1)] underline">Login disini</a></p>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->any())
                let errorMessage = "Registrasi akun tidak berhasil. Please check your input.";
                // @foreach ($errors->all() as $error)
                //     errorMessage += "\n- {{ $error }}";
                // @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error!',
                    text: errorMessage,
                });
            @endif
            
            // toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPasswordInput = document.getElementById('password_confirmation');

            // Function to toggle password visibility for a given input and toggle element
            function setupPasswordToggle(inputElement, toggleElement) {
                if (toggleElement && inputElement) {
                    toggleElement.addEventListener('click', function () {
                        // Toggle the type attribute
                        const type = inputElement.getAttribute('type') === 'password' ? 'text' : 'password';
                        inputElement.setAttribute('type', type);

                        // Toggle the eye icon
                        this.querySelector('i').classList.toggle('fa-eye');
                        this.querySelector('i').classList.toggle('fa-eye-slash');
                    });
                }
            }

            // Setup toggles for both password fields
            setupPasswordToggle(passwordInput, togglePassword);
            setupPasswordToggle(confirmPasswordInput, toggleConfirmPassword);
        });
    </script>
@endpush