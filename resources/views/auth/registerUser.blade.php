@extends('layout')

@section('content')
   <div class="flex max-md:flex-col md:justify-center md:items-center md:h-screen w-screen overflow-x-hidden text-black max-md:pb-24">
        {{-- Left: Logo (only visible on desktop) --}}
        <div class="hidden md:flex md:w-1/2 justify-center items-center">
            <img class="w-3/4 max-w-md" src="{{ asset('assets/ewaps-logo.png')}}" alt="">
        </div>

        <div class="flex flex-col w-full md:w-1/2 h-screen md:h-auto items-center pt-5 pb-20 md:py-10">
        {{-- Mobile-only Logo --}}
        <img class="w-[35%] md:hidden" src="{{ asset('assets/ewaps-logo.png')}}" alt="">
        
        <form class="w-[85%] space-y-4" action="{{ route('register.post')}}" method="POST" id="registerForm">
            @csrf
            {{-- This general error div is no longer needed for AJAX validation errors but kept for conventional Laravel redirects if desired --}}
            <div id="initial-error-container" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul id="initial-error-list" class="mt-3 list-disc list-inside">
                    {{-- Errors will be populated here by Laravel Blade if the page reloads with validation errors --}}
                </ul>
            </div>

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
                <span class="error-message text-red-500 text-sm mt-1" id="name-error"></span>
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
                <span class="error-message text-red-500 text-sm mt-1" id="email-error"></span>
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
                <span class="error-message text-red-500 text-sm mt-1" id="phone-error"></span>
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
                <span class="error-message text-red-500 text-sm mt-1" id="password-error"></span>
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
                <span class="error-message text-red-500 text-sm mt-1" id="password_confirmation-error"></span>
            </div>

            <div class="form-group w-full max-w-md mx-auto flex items-start space-x-2">
                <input
                    type="checkbox"
                    name="terms"
                    id="terms"
                    class="mt-1 w-4 h-4 rounded border-[var(--blue1)] text-[var(--blue1)] focus:ring-[var(--blue1)]"
                    {{ old('terms') ? 'checked' : '' }}
                    required>
                <label for="terms" class="text-gray-700">
                    Saya menyetujui
                    <a href="/terms-of-service" class="text-[var(--blue1)] underline hover:text-blue-700">Syarat Layanan</a>
                    dan
                    <a href="/privacy-policy" class="text-[var(--blue1)] underline hover:text-blue-700">Kebijakan Privasi</a>
                </label>
            </div>
            <span class="error-message text-red-500 text-sm mt-1" id="terms-error"></span>

            <button type="submit" class="btn text-center w-full py-3 rounded-lg text-white font-semibold shadow-lg
                bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)]
                hover:from-purple-600 hover:to-indigo-700
                hover:shadow-xl transition-all duration-300 ease-in-out
                focus:outline-none focus:ring-2 focus:ring-[var(--blueGradient1)] focus:ring-opacity-75 font-bold tracking-wide">
                Buat Akun
            </button>
        </form>

        <p class="mt-5">Sudah punya akun? <a href="{{ route('login')}}" class="text-[var(--blue1)] underline">Login disini</a></p>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check for success message from session (for initial page load, if redirected conventionally)
            // const successMessage = "{{ session('success') }}";
            // if (successMessage.trim() !== '') {
            //     Swal.fire({
            //         icon: 'success',
            //         title: 'Registrasi Berhasil!',
            //         text: successMessage,
            //         showConfirmButton: false,
            //         timer: 3000
            //     });
            // }

            // Function to clear all error highlights and messages from inputs
            function clearErrorState() {
                document.querySelectorAll('input, select, textarea').forEach(el => {
                    el.classList.remove('border-red-500');
                });
                document.querySelectorAll('.error-message').forEach(el => {
                    el.textContent = ''; // Clear error messages
                });
            }

            // --- AJAX Form Submission Logic ---
            const registerForm = document.getElementById('registerForm');
            if (registerForm) {
                registerForm.addEventListener('submit', async function(event) {
                    event.preventDefault(); 

                    clearErrorState(); 

                    Swal.fire({
                        title: 'Mohon Tunggu...',
                        html: 'Sedang memproses registrasi Anda.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const formData = new FormData(registerForm);
                        const response = await fetch(registerForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest', 
                                'Accept': 'application/json' 
                            }
                        });

                        const data = await response.json(); 

                        Swal.close(); 

                        if (response.ok) { 
                            if (data.success) {
                                await Swal.fire({
                                    icon: 'success',
                                    title: 'Registrasi Berhasil!',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan!',
                                    text: data.message || 'Registrasi akun tidak berhasil. Silakan coba lagi.',
                                    showConfirmButton: true,
                                });
                            }
                        } else {
                            if (response.status === 422 && data.errors) {
                                for (const field in data.errors) {
                                    const inputField = document.getElementById(field);
                                    const errorSpan = document.getElementById(`${field}-error`);
                                    if (inputField) {
                                        inputField.classList.add('border-red-500');
                                    }
                                    if (errorSpan) {
                                        errorSpan.textContent = data.errors[field][0];
                                    }
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validasi Gagal!',
                                    text: 'Mohon periksa kembali input Anda yang ditandai.',
                                    showConfirmButton: true,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan!',
                                    text: data.message || 'Registrasi akun tidak berhasil. Silakan coba lagi.',
                                    showConfirmButton: true,
                                });
                            }
                        }

                    } catch (error) {
                        Swal.close(); 
                        console.error('Error during registration:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Network Error!',
                            text: 'Terjadi masalah koneksi atau server. Silakan coba lagi.',
                            showConfirmButton: true,
                        });
                    }
                });
            }

            // --- Password Visibility Toggle Logic ---
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPasswordInput = document.getElementById('password_confirmation');

            function setupPasswordToggle(inputElement, toggleElement) {
                if (toggleElement && inputElement) {
                    toggleElement.addEventListener('click', function () {
                        const type = inputElement.getAttribute('type') === 'password' ? 'text' : 'password';
                        inputElement.setAttribute('type', type);
                        this.querySelector('i').classList.toggle('fa-eye');
                        this.querySelector('i').classList.toggle('fa-eye-slash');
                    });
                }
            }
            setupPasswordToggle(passwordInput, togglePassword);
            setupPasswordToggle(confirmPasswordInput, toggleConfirmPassword);

            @if ($errors->any())
                clearErrorState(); 

                const initialErrorContainer = document.getElementById('initial-error-container');
                const initialErrorList = document.getElementById('initial-error-list');
                initialErrorContainer.classList.remove('hidden');
                initialErrorList.innerHTML = '';

                // Populate initial error list in the dedicated container (if still desired)
                @foreach ($errors->all() as $error)
                    const li = document.createElement('li');
                    li.textContent = "{{ $error }}";
                    initialErrorList.appendChild(li);
                @endforeach

                // Highlight fields that have errors on initial load and display messages
                @foreach ($errors->keys() as $field)
                    const errorField = document.getElementById('{{ $field }}');
                    const errorSpan = document.getElementById('{{ $field }}-error');
                    if (errorField) {
                        errorField.classList.add('border-red-500');
                    }
                    if (errorSpan) {
                        // Display the error message directly from Laravel's error bag
                        errorSpan.textContent = "{{ $errors->first($field) }}";
                    }
                @endforeach

                // You can still show a general SweetAlert on initial load if there are ANY errors
                Swal.fire({
                    icon: 'error',
                    title: 'Pendaftaran Gagal!',
                    html: 'Terdapat masalah dengan input Anda. Silakan periksa kembali formulir.',
                    confirmButtonColor: '#EF4444'
                });
            @endif
        });
    </script>
@endpush