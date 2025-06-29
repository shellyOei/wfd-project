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

        .scroll-transparent {
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
        }

        .scroll-transparent::-webkit-scrollbar {
            height: 6px;
        }

        .scroll-transparent::-webkit-scrollbar-track {
            background: transparent;
        }

        .scroll-transparent::-webkit-scrollbar-thumb {
            background-color: transparent;
        }
    </style>
@endsection
@section('content')
<div class="min-h-screen bg-[#f4f4fd px-4">
    <main class="max-w-[440px] lg:max-w-5xl mx-auto pt-[2rem] pb-[2rem] px-4 lg:px-8">
        <div class="flex items-center mb-6 xl:p-8">
            <button onclick="history.back()"
                class="w-8 h-8 xl:w-12 xl:h-12 xl:hover:bg-gray-200 xl:bg-[#f4f4fd] bg-[#f4f4fd] rounded-full flex items-center justify-center -mr-2">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <h1 class="text-xl xl:text-3xl font-bold flex-1 text-center">Riwayat Janji Temu</h1>
        </div>

        <div class="overflow-x-auto mb-6 scroll-transparent">
            <div class="flex justify-center gap-8 px-2 lg:px-0 min-w-max">
                @foreach ($patients as $index => $data)
                    @php
                        $initial = strtoupper(substr($data['patient_name'], 0, 1));
                        $isSelected = $index === 0 ? 'selected' : '';
                    @endphp
                    <div class="text-center">
                        <div data-id="{{ $data['patient_id'] }}"
                            class="avatar-circle w-16 h-16 lg:w-20 lg:h-20 mx-auto mb-2 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-3xl shadow cursor-pointer hover:bg-blue-200 {{ $isSelected }}">
                            {{ $initial }}
                        </div>
                        <p class="text-[#7a7a7a] text-sm lg:text-base">{{ $data['patient_name'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Janji Temu Aktif --}}
        <section class="mb-10">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-md font-semibold text-[#373737] lg:text-lg">Janji Temu Mendatang</h2>
                <!-- BLM arahin route ke full history n booking -->
                <a href="#" class="text-xs font-bold text-black underline flex items-center whitespace-nowrap">
                    Lihat Selengkapnya
                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="relative">
                @if ($activeAppointments->isEmpty())
                    <div class="text-center text-[#a9a9a9] italic py-4 bg-white rounded-2xl p-4 shadow-sm">
                        Belum ada janji temu.
                    </div>
                @else
                    <div class="scroll-container overflow-x-auto flex gap-4 pb-2" id="active-appointments">
                        @foreach ($activeAppointments as $item)
                            <div class="min-w-[260px] lg:min-w-[300px] bg-white rounded-2xl p-4 shadow-sm">
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

                @if ($activeAppointments->count() >= 2)
                    <div
                        class="absolute top-0 right-0 h-full w-12 pointer-events-none bg-gradient-to-r from-transparent to-[rgba(229,231,235,0.5)] fade-right">
                    </div>
                @endif
            </div>
        </section>

        {{-- Riwayat Janji Temu --}}
        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-md font-semibold text-[#373737] flex items-center gap-2 lg:text-lg">
                    Riwayat
                    <i class="fas fa-history text-xs text-[#6b7280]"></i>
                </h2>
                <!-- BLM arahin route ke full history n booking -->
                <a href="#" class="text-xs font-bold text-black underline flex items-center whitespace-nowrap">
                    Lihat Selengkapnya
                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="relative">
                <div class="scroll-container overflow-x-auto flex gap-4 pb-2" id="history-appointments">
                    @if ($historyAppointments->isEmpty())
                        <div
                            class="text-center text-[#a9a9a9] italic py-4 min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                            Belum ada riwayat janji temu.
                        </div>
                    @else
                        @foreach ($historyAppointments as $item)
                            <div class="min-w-[260px] lg:min-w-[300px] bg-white rounded-2xl p-4 shadow-sm">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-semibold text-[#7a7a7a]">{{ $item['date'] }}</span>
                                    <span class="text-[#7a7a7a] font-bold text-sm">{{ $item['time'] }}</span>
                                </div>
                                <h3 class="text-lg font-bold mb-1">{{ $item['title'] }}</h3>
                                <p class="text-sm font-semibold mb-0.5">{{ $item['doctor_name'] }}</p>
                                <p class="text-[#a9a9a9] text-sm">Dokter {{ $item['specialization'] }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>

                @if ($historyAppointments->count() >= 2)
                    <div
                        class="absolute top-0 right-0 h-full w-12 pointer-events-none bg-gradient-to-r from-transparent to-[rgba(229,231,235,0.5)] fade-right">
                    </div>
                @endif
            </div>
        </section>
    </main>
</div>
@endsection


@push('scripts')

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
                fetch(`/user/mini-history/data/${patientId}`)
                    .then(res => res.json())
                    .then(data => {
                        updateSection('active-appointments', data.activeAppointments, true);
                        updateSection('history-appointments', data.historyAppointments, false);
                    })
                    .catch(err => console.error(err));
            }
            function toggleFade(containerId, itemCount) {
                const wrapper = document.getElementById(containerId).parentElement;
                let fade = wrapper.querySelector('.fade-right');

                if (fade) fade.remove();

                if (itemCount >= 2) {
                    const div = document.createElement('div');
                    div.className = 'absolute top-0 right-0 h-full w-12 pointer-events-none bg-gradient-to-r from-transparent to-[rgba(229,231,235,0.5)] bg-opacity-5 fade-right';
                    wrapper.appendChild(div);
                }
            }


            function updateSection(containerId, items, isActive) {
                toggleFade(containerId, items.length);
                const container = document.getElementById(containerId);
                // console.log('container ID:', containerId);
                // console.log('container element:', container);
                // console.log('items:', items);
                container.innerHTML = '';

                if (!items.length) {
                    container.innerHTML = `<div class="text-center text-[#a9a9a9] italic py-4 min-w-[260px] w-full bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                                                                                            ${isActive ? 'Belum ada janji temu.' : 'Belum ada riwayat janji temu.'}
                                                                                        </div>`;
                    return;
                }

                items.forEach(item => {
                    const dateObj = new Date(item.date);
                    const now = new Date();
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const tomorrow = new Date();
                    tomorrow.setDate(today.getDate() + 1);
                    tomorrow.setHours(0, 0, 0, 0);

                    let dateText = dateObj.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                    let textColor = 'text-[#7a7a7a]';

                    if (dateObj >= today && dateObj < tomorrow) {
                        dateText = 'Hari ini';
                        textColor = 'text-green-500';
                    } else if (dateObj >= tomorrow && dateObj < new Date(tomorrow.getTime() + 86400000)) {
                        dateText = 'Besok';
                        textColor = 'text-yellow-500';
                    }
                    const card = document.createElement('div');
                    card.className = 'min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0';

                    card.innerHTML = `
                                                                                            <div class="flex justify-between items-center mb-1">
                                                                                                <span class="text-sm font-semibold ${textColor}">${dateText}</span>
                                                                                                <span class="text-[#7a7a7a] font-bold text-sm">${item.time}</span>
                                                                                            </div>
                                                                                            <h3 class="text-lg font-bold mb-1">${item.title}</h3>
                                                                                            <p class="text-sm font-semibold mb-0.5">${item.doctor_name}</p>
                                                                                            <p class="text-[#a9a9a9] text-sm">Dokter ${item.specialization}</p>
                                                                                        `;
                    container.appendChild(card);
                    // console.log('Card ditambahkan:', card);
                });
            }
        });
    </script>
@endpush