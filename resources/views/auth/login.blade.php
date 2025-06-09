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
            <div class="form-group w-full max-w-md mx-auto flex flex-col">
                <label class="font-semibold text-md" for="password">Password</label>
                <div class="relative">
                    <input
                        type="password"
                        placeholder="***"
                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400"
                        name="password" id="password" class="form-control" required>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
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
                        