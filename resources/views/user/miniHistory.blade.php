@extends('layout')

@section('style')
    <style>
        .bottom-nav-shadow {
            box-shadow: 0px 4px 15px 4px rgba(0, 0, 0, 0.25);
        }

        .scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #8FB7FF rgba(255, 255, 255, .15);
        }

        .scroll-container::-webkit-scrollbar {
            height: 6px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, .15);
            border-radius: 4px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            border-radius: 4px;
            background: linear-gradient(90deg,
                    #4ADEDE 0%,
                    #1CA7EC 50%,
                    #1F2F98 100%);
            box-shadow: 0 0 4px 2px rgba(28, 167, 236, .25) inset;
            transition: background-color .25s;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(90deg,
                    #1F2F98 0%,
                    #1CA7EC 50%,
                    #4ADEDE 100%);
        }

        ✨ Why this fits the scr
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
            <!-- Title -->
            <div class="flex items-center px-6 mt-2 mb-4">
                <button onclick="history.back()"
                    class="w-8 h-8 bg-[#f4f4fd] rounded-full flex items-center justify-center mr-2">
                    <i class="fas fa-chevron-left text-lg"></i>
                </button>
                <h1 class="text-xl font-bold flex-1 text-center">Riwayat Janji Temu</h1>
            </div>

            <!-- Profile Avatars -->
            <div class="flex justify-center gap-8 mt-2 mb-6">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-2">
                        <img src="img/mask-group.png" alt="Angel"
                            class="w-16 h-16 rounded-full mx-auto object-cover border-2 border-white shadow" />
                    </div>
                    <p class="text-[#7a7a7a] text-sm">Angel</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-2">
                        <img src="img/mask-group-2.png" alt="Chris"
                            class="w-16 h-16 rounded-full mx-auto object-cover border-2 border-white shadow" />
                    </div>
                    <p class="text-[#7a7a7a] text-sm">Chris</p>
                </div>
            </div>

            <!-- Janji Temu Aktif -->
            <section class="px-6 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-lg font-semibold text-[#373737]">Janji Temu Aktif</h2>
                    <a href="#" class="text-xs font-bold text-black underline flex items-center whitespace-nowrap">
                        Lihat Selengkapnya
                        <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="relative">
                    <div class="scroll-container overflow-x-auto flex gap-4 pb-2">
                        <!-- Card 1: Hari Ini -->
                        <div class="min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[#18cf00] text-sm font-semibold">Hari Ini</span>
                                <span class="text-[#7a7a7a] font-bold text-sm">12.30</span>
                            </div>
                            <h3 class="text-lg font-bold mb-1">Pengecekan Darah</h3>
                            <p class="text-sm font-semibold mb-0.5">dr. Oh Yi Young</p>
                            <p class="text-[#a9a9a9] text-sm">Dokter Umum</p>
                        </div>
                        <!-- Card 2: Besok -->
                        <div class="min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[#7a7a7a] text-sm font-semibold">Besok</span>
                                <span class="text-[#7a7a7a] font-bold text-sm">11.30</span>
                            </div>
                            <h3 class="text-lg font-bold mb-1">Pengecekan Darah</h3>
                            <p class="text-sm font-semibold mb-0.5">dr. Jessi S.pd</p>
                            <p class="text-[#a9a9a9] text-sm">Dokter Umum</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Riwayat -->
            <section class="px-6">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-lg font-semibold text-[#373737]">Riwayat</h2>
                    <a href="#" class="text-xs font-bold text-black underline flex items-center whitespace-nowrap">
                        Lihat Selengkapnya
                        <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="relative">
                    <div class="scroll-container overflow-x-auto flex gap-4 pb-2">
                        <!-- Card 1: General Checkup -->
                        <div class="min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[#7a7a7a] text-sm">27 November 2024</span>
                                <span class="text-[#7a7a7a] font-bold text-sm">17.00</span>
                            </div>
                            <h3 class="text-lg font-bold mb-1">General Checkup</h3>
                            <p class="text-sm font-semibold mb-0.5">dr. Andrew S.pd</p>
                            <p class="text-[#a9a9a9] text-sm">Dokter Umum</p>
                        </div>
                        <!-- Card 2: Pengecekan Darah -->
                        <div class="min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[#7a7a7a] text-sm">2 Januari</span>
                                <span class="text-[#7a7a7a] font-bold text-sm">11.30</span>
                            </div>
                            <h3 class="text-lg font-bold mb-1">Pengecekan Darah</h3>
                            <p class="text-sm font-semibold mb-0.5">Dr. Oh Yi Young</p>
                            <p class="text-[#a9a9a9] text-sm">Dokter Umum</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Bottom Navigation -->
        <!-- <nav class="fixed bottom-5 left-5 right-5 h-[61px] bg-white rounded-full bottom-nav-shadow flex justify-around items-center">
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