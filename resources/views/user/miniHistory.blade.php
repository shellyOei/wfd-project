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

        .selected {
            border: 3px solid #1CA7EC;
        }
    </style>
@endsection

@section('content')
<div class="min-h-screen bg-[#f4f4fd]">
    <main class="max-w-[440px] mx-auto pt-[63px] pb-[61px]">
        <div class="flex items-center px-6 mt-2 mb-4">
            <button onclick="history.back()"
                class="w-8 h-8 bg-[#f4f4fd] rounded-full flex items-center justify-center mr-2">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <h1 class="text-xl font-bold flex-1 text-center">Riwayat Janji Temu</h1>
        </div>

        <div class="flex justify-center gap-8 mt-6 mb-6">
            @foreach ($patients as $index => $data)
                @php
                    $initial = strtoupper(substr($data['patient_name'], 0, 1));
                    $isSelected = $index === 0 ? 'selected' : '';
                @endphp
                <div class="text-center">
                    <div data-id="{{ $data['patient_id'] }}"
                        class="avatar-circle w-16 h-16 mx-auto mb-2 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-3xl shadow cursor-pointer hover:bg-blue-200 {{ $isSelected }}">
                        {{ $initial }}
                    </div>
                    <p class="text-[#7a7a7a] text-sm">{{ $data['patient_name'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Janji Temu Aktif --}}
        <section class="px-6 mb-6">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-[#373737]">Janji Temu Aktif</h2>
                <a href="#" class="text-xs font-bold text-black underline flex items-center whitespace-nowrap">
                    Lihat Selengkapnya
                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="relative">
                @if ($activeAppointments->isEmpty())
                    <div
                        class="text-center text-[#a9a9a9] italic py-4 min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                        Belum ada riwayat janji temu.
                    </div>
                @else
                <div class="scroll-container overflow-x-auto flex gap-4 pb-2" id="active-appointments">
                    @forelse ($activeAppointments as $item)
                    <div class="min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-semibold text-[#7a7a7a]">{{ $item['date'] }}</span>
                            <span class="text-[#7a7a7a] font-bold text-sm">{{ $item['time'] }}</span>
                        </div>
                        <h3 class="text-lg font-bold mb-1">{{ $item['title'] }}</h3>
                        <p class="text-sm font-semibold mb-0.5">{{ $item['doctor_name'] }}</p>
                        <p class="text-[#a9a9a9] text-sm">Dokter {{ $item['specialization'] }}</p>
                    </div>
                    @endforeach
                </div>
                @endif

                <div
                    class="absolute top-0 right-0 h-full w-12 pointer-events-none bg-gradient-to-r from-transparent to-[rgba(229,231,235,0.5)] bg-opacity-5">
                </div>
            </div>
        </section>

        {{-- Riwayat Janji Temu --}}
        <section class="px-6">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-[#373737]">Riwayat</h2>
                <a href="#" class="text-xs font-bold text-black underline flex items-center whitespace-nowrap">
                    Lihat Selengkapnya
                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="relative">
                @if ($historyAppointments->isEmpty())
                    <div
                        class="text-center text-[#a9a9a9] italic py-4 min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                        Belum ada riwayat janji temu.
                    </div>
                @else
                    <div class="scroll-container overflow-x-auto flex gap-4 pb-2" id="history-appointments">
                        @foreach ($historyAppointments as $item)
                            <div class="min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-semibold text-[#7a7a7a]">{{ $item['date'] }}</span>
                                    <span class="text-[#7a7a7a] font-bold text-sm">{{ $item['time'] }}</span>
                                </div>
                                <h3 class="text-lg font-bold mb-1">{{ $item['title'] }}</h3>
                                <p class="text-sm font-semibold mb-0.5">{{ $item['doctor_name'] }}</p>
                                <p class="text-[#a9a9a9] text-sm">Dokter {{ $item['specialization'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div
                    class="absolute top-0 right-0 h-full w-12 pointer-events-none bg-gradient-to-r from-transparent to-[rgba(229,231,235,0.5)] bg-opacity-5">
                </div>
            </div>
        </section>
    </main>
</div>
@endsection

@push('scripts')
        <!-- BLM bisa klik pasien lain and load grr -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const avatars = document.querySelectorAll('.avatar-circle');

            // Load janji temu untuk pasien pertama
            const firstAvatar = avatars[0];
            if (firstAvatar) {
                const firstId = firstAvatar.getAttribute('data-id');
                fetchData(firstId);
            }

            avatars.forEach(avatar => {
                avatar.addEventListener('click', function () {
                    avatars.forEach(a => a.classList.remove('selected'));
                    this.classList.add('selected');
                    

                    const patientId = this.getAttribute('data-id');
                    fetchData(patientId);
                });
            });

            function fetchData(patientId) {
                fetch(`/history/data/${patientId}`)
                    .then(res => res.json())
                    .then(data => {
                        updateSection('active-appointments', data.activeAppointments, true);
                        updateSection('history-appointments', data.historyAppointments, false);
                    })
                    .catch(err => console.error(err));
            }

            function updateSection(containerId, items, isActive) {
                const container = document.getElementById(containerId);
                container.innerHTML = '';

                if (!items.length) {
                    container.innerHTML = `<div class="text-center text-[#a9a9a9] italic py-4 min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                                        ${isActive ? 'Belum ada janji temu yang akan datang.' : 'Belum ada riwayat janji temu.'}
                                    </div>`;
                    return;
                }

                items.forEach(item => {
                    const card = document.createElement('div');
                    card.className = 'min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0';

                    card.innerHTML = `
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-semibold text-[#7a7a7a]">${item.date}</span>
                                            <span class="text-[#7a7a7a] font-bold text-sm">${item.time}</span>
                                        </div>
                                        <h3 class="text-lg font-bold mb-1">${item.title}</h3>
                                        <p class="text-sm font-semibold mb-0.5">${item.doctor_name}</p>
                                        <p class="text-[#a9a9a9] text-sm">Dokter ${item.specialization}</p>
                                    `;
                    container.appendChild(card);
                });
            }
        });
    </script>
@endpush