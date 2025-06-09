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
                <!-- Account Section -->
                <h1 class="text-2xl font-bold mt-5 mb-5">Akun</h1>
                <div class="flex items-center bg-white rounded-2xl p-4 mb-4">
                    <img class="w-[88px] h-[87px] rounded-full mr-4" src="img/mask-group.png" alt="User avatar" />
                    <div>
                        <h2 class="text-xl font-bold">Angel</h2>
                        <p class="text-[#7a7a7a]">angel@gmail.com</p>
                    </div>
                </div>

                <!-- Patient List Section -->
                <section class="relative">
                    <div class="flex justify-between items-center mb-5">
                        <h2 class="text-2xl font-bold">Daftar Pasien</h2>
                        <a href="#" class="text-xs font-bold underline flex items-center">
                            Lihat Selengkapnya
                            <i class="fas fa-chevron-right text-[10px] ml-1"></i>
                        </a>
                    </div>

                    <!-- Patient Cards -->
                    <div class="space-y-4 mb-4">
                        <div class="flex items-center bg-white rounded-2xl p-4">
                            <img class="w-[88px] h-[87px] rounded-full mr-4" src="img/mask-group-2.png" alt="User avatar" />
                            <div>
                                <h3 class="text-xl font-bold">Angel</h3>
                                <p class="text-[#7a7a7a]">Profil Utama</p>
                            </div>
                        </div>

                        <div class="flex items-center bg-white rounded-2xl p-4">
                            <img class="w-[88px] h-[87px] rounded-full mr-4" src="img/image.png" alt="User avatar" />
                            <div>
                                <h3 class="text-xl font-bold">Chris Parker</h3>
                                <p class="text-[#7a7a7a]">Pasangan</p>
                            </div>
                        </div>
                    </div>

                    <button
                        class="w-full gradient-bg bg-gradient-to-r hover:bg-gradient-to-bl transition duration-200 ease-in-out transform hover:scale-110 from-[#4ADEDE] via-[#1CA7EC] to-[#1F2F98] text-white rounded-xl py-2.5 text-xl font-bold shadow-md">
                        <i class="fas fa-plus mr-2"></i>Tambah (2/5)
                    </button>

                </section>

                <!-- Settings Section -->
                <section class="mt-8">
                    <h2 class="text-2xl font-bold mb-5">Pengaturan</h2>
                    <div class="bg-white rounded-2xl overflow-hidden">
                        <a href="#" class="flex items-center ml-2 p-4 border-b border-gray-100">
                            <i class="fas fa-history text-2xl mr-4 text-gray-600"></i>
                            <span class="flex-grow">Riwayat Janji Temu</span>
                            <i class="fas fa-chevron-right text-[10px] mr-2"></i>
                        </a>
                        <a href="#" class="flex items-center ml-2 p-4 border-b border-gray-100">
                            <i class="fas fa-user text-2xl mr-4 text-gray-600"></i>
                            <span class="flex-grow">Edit Akun</span>
                            <i class="fas fa-chevron-right text-[10px] mr-2"></i>
                        </a>
                        <a href="#" class="flex items-center ml-2 p-4 border-b border-gray-100">
                            <i class="fas fa-lock text-2xl mr-4 text-gray-600"></i>
                            <span class="flex-grow">Privasi dan Keamanan</span>
                            <i class="fas fa-chevron-right text-[10px] mr-2"></i>
                        </a>
                        <a href="#" class="flex items-center ml-2 p-4">
                            <i class="fas fa-question-circle text-2xl mr-4 text-gray-600"></i>
                            <span class="flex-grow">Bantuan</span>
                            <i class="fas fa-chevron-right text-[10px] mr-2"></i>
                        </a>
                    </div>

                    <button
                        class="w-full mt-5 flex items-center justify-center border border-[#cf0003] text-[#cf0003] rounded-xl py-2.5 text-xl font-semibold">
                        <i class="fas fa-sign-out-alt mr-2.5"></i>
                        Logout
                    </button>
                </section>
            </section>
        </main>
    </div>
@endsection