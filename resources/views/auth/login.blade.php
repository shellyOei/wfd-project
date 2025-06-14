@extends('layout')

@section('style')
    
@endsection

@section('content')
    <div class="flex flex-col max-h-screen h-screen w-screen overflow-x-hidden items-center pt-5 text-black">
        {{-- logo --}}
        <img class="w-[35%]" src="{{ asset('assets/ewaps-logo.png')}}" alt="">

        <form class="w-[85%] space-y-4" action="{{ route('login') }}" method="POST">
            @csrf
             <div class="form-group w-full max-w-md mx-auto flex flex-col space-y-1">
                <label class="font-semibold text-md" for="email">Email</label>
                <div class="relative">
                    <input
                        type="email"
                        placeholder="Contoh: endawati@example.com"
                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400"
                        name="email" id="email" class="form-control" required>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-gray-400"></i>
                    </div>
                </div>
            </div>
             <div class="form-group w-full max-w-md mx-auto flex flex-col space-y-1">
                <label class="font-semibold text-md" for="password">Password</label>
                <div class="relative">
                    <input
                        type="password"
                        placeholder="Masukkan Password"
                        class="w-full pl-4 pr-10 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent"
                        name="password" id="password" required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" id="togglePassword">
                        <i class="fa-solid fa-eye-slash text-gray-400"></i>
                    </div>
                </div>
            </div>


            <p class="text-right font-semibold underline">Lupa Password?</p>
           
            <button type="submit" class="btn text-center w-full py-3 rounded-lg text-white font-semibold shadow-lg
                bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)]
                hover:from-purple-600 hover:to-indigo-700
                hover:shadow-xl transition-all duration-300 ease-in-out
                focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-opacity-75 font-bold tracking-wide">
                Login
            </button>
        </form>
        <div class="w-[80%] bg-black h-[0.75px] my-6"></div>

        <p>Belum punya akun? <a href="{{ route('register.index')}}"><span class="text-[var(--blue1)] underline">Daftar disini</span></a></p>
    <div>
@endsection
                        
@section('script')
<script>
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
</script>
@endsection