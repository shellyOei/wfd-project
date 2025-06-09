@extends('layout')

@section('style')
    <style>
        /* Only keep necessary custom CSS that can't be done with Tailwind */
        .bottom-nav-shadow {
            box-shadow: 0px 4px 15px 4px rgba(0, 0, 0, 0.25);
        }
        
        /* Custom input focus styles */
        .input-focus {
            @apply border-[#0d1ce3] bg-white;
        }
        
        .input-default {
            @apply border-[#497fff] bg-white/80;
        }
    </style>
@endsection

@section('content')
<div class="min-h-screen bg-[#f4f4fd]">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 h-[63px] bg-[#f4f4fd] z-10">
        <div class="flex justify-between items-center px-7 pt-6">
            <div class="flex gap-1">
                <i class="fas fa-signal text-[22px]"></i>
                <i class="fas fa-wifi text-[22px]"></i>
                <i class="fas fa-battery-full text-[22px]"></i>
            </div>
            <time class="font-semibold text-base">12:45</time>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-[440px] mx-auto pt-[63px] pb-[61px]">
        <!-- Back Button and Title -->
        <div class="flex items-center px-6 mb-8">
            <button onclick="history.back()" class="w-8 h-8 bg-[#f4f4fd] rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-arrow-left text-sm"></i>
            </button>
            <h1 class="text-2xl font-bold">Edit Akun</h1>
        </div>

        <!-- Profile Image -->
        <div class="flex justify-center mb-8">
            <img src="img/mask-group.png" alt="Profile" class="w-[151px] h-[149px] rounded-full" />
        </div>

        <!-- Form -->
        <form class="px-6">
            <!-- Name Field -->
            <div class="mb-6">
                <label class="block text-[15px] mb-2">Nama</label>
                <div class="relative">
                    <input type="text" 
                           class="w-full h-12 px-3 py-2 bg-white/80 border-2 border-[#497fff] rounded-lg focus:outline-none focus:border-[#0d1ce3] focus:bg-white" 
                           placeholder="John Doe" />
                </div>
            </div>

            <!-- Email Field -->
            <div class="mb-6">
                <label class="block text-[15px] mb-2">Email</label>
                <div class="relative">
                    <input type="email" 
                           class="w-full h-12 px-3 py-2 bg-white/80 border-2 border-[#497fff] rounded-lg focus:outline-none focus:border-[#0d1ce3] focus:bg-white" 
                           placeholder="abc@gmail.com" />
                </div>
            </div>

            <!-- Phone Field -->
            <div class="mb-6">
                <label class="block text-[15px] mb-2">Nomor telepon</label>
                <div class="relative">
                    <input type="tel" 
                           class="w-full h-12 px-3 py-2 bg-white/80 border-2 border-[#497fff] rounded-lg focus:outline-none focus:border-[#0d1ce3] focus:bg-white" 
                           placeholder="08123456789" />
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-8">
                <label class="block text-[15px] mb-2">Ubah Password</label>
                <div class="relative">
                    <input type="password" 
                           class="w-full h-12 px-3 py-2 bg-white/80 border-2 border-[#497fff] rounded-lg focus:outline-none focus:border-[#0d1ce3] focus:bg-white" 
                           placeholder="Masukkan password" />
                </div>
            </div>

            <!-- Save Button -->
            <button type="submit" class="w-full h-12 bg-[#497fff] text-white font-bold rounded-lg flex items-center justify-center gap-2">
                <i class="fas fa-save"></i>
                <span>Simpan</span>
            </button>
        </form>
    </main>

    
</div>
@endsection