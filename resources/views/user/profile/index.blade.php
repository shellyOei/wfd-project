@extends('layout')

@section('style')

@endsection

@section('content')
    <div class="min-h-screen pb-20 px-4">
        <!-- Header - Mobile Only -->
        <!-- <header class="fixed top-0 left-0 right-0 h-[63px] bg-[#f4f4fd] z-10 md:hidden">
                                                <div class="flex justify-between items-center px-7 pt-6">
                                                    <div class="flex gap-1">
                                                        <i class="fas fa-signal text-[22px]"></i>
                                                        <i class="fas fa-wifi text-[22px]"></i>
                                                        <i class="fas fa-battery-full text-[22px]"></i>
                                                    </div>
                                                    <time class="font-semibold text-base">12:45</time>
                                                </div>
                                            </header> -->

        <!-- Main Content -->
        <main class="max-w-[440px] md:max-w-7xl mx-auto md:pt-8 pb-[61px] md:pb-8">
            <!-- Desktop Grid Layout -->
            <div class="md:grid md:grid-cols-12 md:gap-8">
                <!-- Left Column - Account & Patient List -->
                <div class="md:col-span-8 px-9 md:px-0">
                    <!-- Account Section -->
                    <section class="mb-8">
                        <h2 class="text-2xl md:text-3xl font-bold mt-5 md:mt-0 mb-5">Akun</h2>
                        <div
                            class="flex items-center bg-white rounded-2xl p-4 md:p-6 mb-4 shadow-sm hover:shadow-md transition-shadow">
                            <img class="w-[88px] h-[87px] md:w-20 md:h-20 rounded-full mr-4 md:mr-6"
                                src="img/mask-group.png" alt="User avatar" />
                            <div class="flex-grow">
                                <h3 class="text-xl md:text-2xl font-bold">{{ $user->name }}</h3>
                                <p class="text-[#7a7a7a] md:text-lg">{{ $user->email }}</p>
                            </div>
                        </div>
                    </section>

                    <!-- Patient List Section -->
                    <section class="relative">
                        <div class="flex justify-between items-center mb-5">
                            <h2 class="text-2xl md:text-3xl font-bold">Daftar Pasien</h2>
                            <a href="{{ route('user.patients') }}"
                                class="text-sm md:text-lg font-bold underline flex items-center text-blue-600 hover:text-blue-800">
                                Edit
                                <i class="fas fa-chevron-right text-[10px] ml-1"></i>
                            </a>
                        </div>

                        <!-- Patient Cards -->
                        <div class="space-y-4 mb-4 md:grid md:grid-cols-1 md:gap-4 md:space-y-0">
                            @foreach($user->profiles as $profile)
                                @if($profile->patient)
                                    <div class="flex items-center bg-white rounded-2xl p-4 md:p-6 transition-shadow">
                                        @php
                                            $initial = strtoupper(substr($profile->patient->name, 0, 1));
                                        @endphp

                                        <div
                                            class="w-[88px] h-[87px] md:w-20 md:h-20 rounded-full mr-4 md:mr-6 flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-4xl">
                                            {{ $initial }}
                                        </div>
                                        <div class="flex-grow">
                                            <h3 class="text-xl md:text-xl font-bold">{{ $profile->patient->name }}</h3>
                                            <!-- <p class="text-[#7a7a7a] md:text-base">Profil Utama</p> -->
                                            <div class="flex flex-wrap items-center gap-2 mt-1 mb-2">
                                                <span
                                                    class="bg-gray-100 text-gray-600 text-xs font-medium md:px-4 md:py-1 px-2 py-1 rounded-full">
                                                    {{ $profile->patient->sex === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                                </span>

                                                <!-- @php
                                                                            $umur = Carbon\Carbon::parse($profile->patient->date_of_birth)->age;
                                                                        @endphp
                                                                        <span
                                                                            class="bg-gray-100 text-gray-600 text-xs font-medium px-4 py-1 md:py-1 px-2 py-1 rounded-full">
                                                                            {{ $umur }} tahun
                                                                        </span> -->
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <!-- <div class="flex items-center bg-white rounded-2xl p-4 md:p-6 shadow-sm hover:shadow-md transition-shadow">
                                                                    <img class="w-[88px] h-[87px] md:w-20 md:h-20 rounded-full mr-4 md:mr-6" src="img/image.png" alt="User avatar" />
                                                                    <div class="flex-grow">
                                                                        <h3 class="text-xl md:text-xl font-bold">Chris Parker</h3>
                                                                        <p class="text-[#7a7a7a] md:text-base">Pasangan</p>
                                                                        <div class="hidden md:block mt-2 text-sm text-gray-500">
                                                                            Last visit: 1 week ago
                                                                        </div>
                                                                    </div>

                                                                </div> -->
                        </div>

                        @php
                            $jumlah_terhubung = $user->profiles->count();
                        @endphp

                        <button id="patient-modal-btn"
                            class="w-full gradient-bg bg-gradient-to-r hover:bg-gradient-to-bl transition duration-200 ease-in-out transform hover:scale-105 md:hover:scale-102 from-[#4ADEDE] via-[#1CA7EC] to-[#1F2F98] text-white rounded-xl py-2.5 md:py-3 text-xl font-bold shadow-md">
                            <i class="fas fa-plus mr-2"></i>Tambah ({{ $jumlah_terhubung }}/5)
                        </button>
                    </section>
                </div>

                <!-- Right Column - Settings (Desktop) / Full Width (Mobile) -->
                <div class="md:col-span-4 px-9 md:px-0">
                    <!-- Settings Section -->
                    <section class="mt-8 md:mt-0">
                        <h2 class="text-2xl md:text-3xl font-bold mb-5">Pengaturan</h2>
                        <div class="bg-white rounded-2xl overflow-hidden shadow-sm ">
                            <a href="{{ route('user.miniHistory') }}"
                                class="flex items-center ml-2 p-4 md:p-5 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-history text-2xl mr-4 text-gray-600"></i>
                                <span class="flex-grow md:text-lg">Riwayat Janji Temu</span>
                                <i class="fas fa-chevron-right text-[10px] mr-2"></i>
                            </a>
                            <a href="{{ route('user.update') }}"
                                class="flex items-center ml-2 p-4 md:p-5 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-user text-2xl mr-4 text-gray-600"></i>
                                <span class="flex-grow md:text-lg">Edit Akun</span>
                                <i class="fas fa-chevron-right text-[10px] mr-2"></i>
                            </a>
                            <a href="#"
                                class="flex items-center ml-2 p-4 md:p-5 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-lock text-2xl mr-4 text-gray-600"></i>
                                <span class="flex-grow md:text-lg">Privasi dan Keamanan</span>
                                <i class="fas fa-chevron-right text-[10px] mr-2"></i>
                            </a>
                            <a href="#" class="flex items-center ml-2 p-4 md:p-5 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-question-circle text-2xl mr-4 text-gray-600"></i>
                                <span class="flex-grow md:text-lg">Bantuan</span>
                                <i class="fas fa-chevron-right text-[10px] mr-2"></i>
                            </a>
                        </div>

                        <!-- Desktop Additional Settings -->
                        <div class="hidden md:block mt-6">
                            <div class="bg-white rounded-2xl p-6 shadow-sm">
                                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                                <div class="space-y-3">
                                    <button
                                        class="w-full text-left px-4 py-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-calendar-plus mr-3 text-blue-600"></i>
                                        <span class="text-blue-800 font-medium">Schedule Appointment</span>
                                    </button>
                                    <button
                                        class="w-full text-left px-4 py-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                        <i class="fas fa-file-medical mr-3 text-green-600"></i>
                                        <span class="text-green-800 font-medium">View Medical Records</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- <form method="POST" action="{{ route('user.logout') }}">
                                @csrf -->
                        <a href="{{ route('user.logout') }}"
                            class="w-full mt-5 flex items-center justify-center border border-[#cf0003] text-[#cf0003] rounded-xl py-2.5 md:py-3 text-xl font-semibold hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2.5"></i>
                            Logout
                        </a>

                        <!-- </form> -->

                    </section>
                </div>
            </div>
        </main>
    </div>
@endsection
@push('scripts')
<script>
    sessionStorage.setItem('prevUrl', window.location.href);
</script>
