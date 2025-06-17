@extends('layout')

@section('style')

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
            <section class="px-9">
                <!-- Back Button and Title -->
                <div class="flex items-center mb-8">
                    <button onclick="history.back()"
                        class="w-8 h-8 bg-[#f4f4fd] rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </button>
                    <h1 class="text-2xl font-bold">Daftar Pasien</h1>
                </div>

                <!-- Main Profile -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-5">
                        <h2 class="text-2xl font-bold">Profil Utama</h2>
                        <a href="#" class="text-sm text-[#005fcf] mr-4 flex items-center">
                            Ganti
                        </a>
                    </div>
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <div class="flex items-center">
                                <img class="w-[88px] h-[87px] rounded-full mr-4" src="img/mask-group.png"
                                    alt="User avatar" />
                                <div class="flex-grow">
                                    <h3 class="text-xl font-bold">Angel</h3>
                                    <p class="text-[#7a7a7a]">Profil Utama</p>
                                    <div class="flex gap-2 mt-2">
                                        <span
                                            class="bg-[#eef5ff] text-[#005fcf] text-xs px-3 py-0.5 rounded-full">Perempuan</span>
                                        <span class="bg-[#eef5ff] text-[#005fcf] text-xs px-3 py-0.5 rounded-full">32
                                            Tahun</span>
                                    </div>
                                </div>
                                <a href="#" class="text-[#005fcf]">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other Profiles -->
                <div>
                    <h2 class="text-2xl font-medium text-[#373737] mb-4">Profil Lainnya</h2>
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <div class="flex items-center">
                                <img class="w-[88px] h-[87px] rounded-full mr-4" src="img/image.png" alt="User avatar" />
                                <div class="flex-grow">
                                    <h3 class="text-xl font-bold">Chris Parker</h3>
                                    <p class="text-[#7a7a7a]">Pasangan</p>
                                    <div class="flex gap-2 mt-2">
                                        <span
                                            class="bg-[#eef5ff] text-[#005fcf] text-xs px-3 py-0.5 rounded-full">Laki-Laki</span>
                                        <span class="bg-[#eef5ff] text-[#005fcf] text-xs px-3 py-0.5 rounded-full">33
                                            Tahun</span>
                                    </div>
                                </div>
                                <a href="#" class="text-[#005fcf]">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Bottom Navigation
        <nav class="fixed bottom-5 left-5 right-5 h-[61px] bg-white rounded-full bottom-nav-shadow flex justify-around items-center">
            <a href="#" class="flex flex-col items-center text-[#a9a9a9] text-xs">
                <i class="fas fa-home text-2xl mb-1"></i>
                <span>Home</span>
            </a>
            <a href="#" class="flex flex-col items-center text-[#a9a9a9] text-xs">
                <i class="fas fa-clipboard text-2xl mb-1"></i>
                <span>Appointment</span>
            </a>
            <a href="#" class="flex flex-col items-center text-[#a9a9a9] text-xs relative">
                <div class="relative">
                    <i class="fas fa-phone text-2xl mb-1"></i>
                    <i class="fas fa-exclamation-triangle text-red-500 absolute -top-1 -right-1 text-sm"></i>
                </div>
                <span>SOS</span>
            </a>
            <a href="#" class="flex flex-col items-center text-[#a9a9a9] text-xs">
                <i class="fas fa-calendar-plus text-2xl mb-1"></i>
                <span>Booking</span>
            </a>
            <a href="#" class="flex flex-col items-center text-[#497fff] text-xs">
                <i class="fas fa-user text-2xl mb-1"></i>
                <span>Profile</span>
            </a>
        </nav> -->
    </div>
@endsection