@extends('layout') {{-- Pastikan ini mengarah ke layout utama Anda --}}

@section('content')
<div class="max-w-xl mx-auto p-4 min-h-screen font-sans antialiased pb-24">
    <div class="flex items-center mb-6 mt-4">
        <a href="{{ url()->previous() }}" class="mr-2 text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-xl font-bold flex-grow text-center text-gray-800">Pilih Tanggal & Waktu</h1>
        <div class="w-6"></div> {{-- Placeholder untuk keseimbangan layout --}}
    </div>

    <div class="bg-white p-4 rounded-xl shadow-md mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">
            {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('F Y') }} {{-- Menampilkan bulan dan tahun --}}
        </h2>

        {{-- Container untuk tanggal yang bisa di-scroll --}}
        <div class="flex justify-center">
            <div class="flex overflow-x-auto pb-4 custom-scrollbar" id="date-picker-container">
                @foreach ($bookingDates as $dateItem)
                    <div class="flex-none w-20 mx-1 date-item
                                @if($selectedDate == $dateItem['full_date']) bg-gradient-to-b from-yellow-400 to-orange-500 text-white shadow-lg
                                @else text-gray-700 bg-gray-100 hover:bg-gray-200 @endif
                                rounded-lg py-2 cursor-pointer transition-all duration-300
                                flex flex-col justify-center items-center h-full"
                        data-date="{{ $dateItem['full_date'] }}"
                        data-doctor-id="{{ $doctor->id }}" {{-- Diperlukan untuk AJAX request --}}
                    >
                        <div class="text-lg font-bold">{{ $dateItem['day_name'] }}</div>
                        <div class="text-sm">{{ $dateItem['date_num'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="text-sm text-gray-500 mt-4 text-center">
            <strong class="text-blue-600">Info:</strong> Booking tersedia hingga 7 hari ke depan.
        </p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-md mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Waktu yang Tersedia</h2>
        <div class="grid grid-cols-3 gap-3" id="time-slots-grid">
            {{-- Loop untuk menampilkan slot waktu dari $times --}}
            @forelse ($times as $slot)
                <div class="time-item
                        @if($slot['isBooked']) 
                            bg-red-100 text-red-700 cursor-not-allowed opacity-60
                        @elseif(isset($selectedTime) && $selectedTime == $slot['time']) 
                            bg-gradient-to-r from-cyan-400 to-blue-600 text-white shadow-lg
                        @else
                            bg-blue-50 text-blue-900 hover:bg-blue-100 cursor-pointer
                        @endif" 
                    data-time="{{ $slot['time'] }}">
                    {{ $slot['time'] }}
                </div>
            @empty
                {{-- Pesan ini akan disembunyikan/ditampilkan oleh JS --}}
                <p class="col-span-3 text-center text-gray-600" id="no-schedule-message">Tidak ada jadwal tersedia untuk tanggal ini.</p>
            @endforelse
        </div>
    </div>

    <button id="confirmBookingBtn" class="w-full bg-gradient-to-r from-cyan-400 to-blue-600 text-white font-bold py-3 rounded-xl shadow-md hover:opacity-90 transition">
        Konfirmasi Booking
    </button>
</div>

{{-- CSS untuk custom scrollbar --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #a0aec0;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background-color: #edf2f7;
        border-radius: 3px;
    }

    /* Menambahkan tinggi minimum agar item tanggal terlihat bagus */
    .date-item {
        min-height: 80px; /* Sesuaikan sesuai kebutuhan desain Anda */
    }
</style>
@endsection

@section('script')
{{-- SweetAlert2 CDN (Pastikan ini dimuat di bagian <head> atau sebelum script ini) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';

    document.addEventListener('DOMContentLoaded', function() {
        const patientId = "{{ $patient->id ?? 'null' }}";
        const dayAvailableId = "{{ $dayAvailable->id ?? null }}"; 
        const dateItems = document.querySelectorAll('.date-item');
        const timeGrid = document.getElementById('time-slots-grid');
        let noScheduleMessage = document.getElementById('no-schedule-message'); // Referensi pesan "tidak ada jadwal"

        const confirmBookingBtn = document.getElementById('confirmBookingBtn');

        let currentSelectedDate = '{{ $selectedDate }}';
        let currentSelectedTime = '{{ $selectedTime ?? '' }}';
        let currentSelectedScheduleId = ''; // Menyimpan ID PracticeSchedule yang dipilih

        // Inisialisasi currentSelectedScheduleId jika ada waktu yang sudah terpilih saat halaman dimuat
        const initialSelectedTimeElement = document.querySelector('.time-item.bg-gradient-to-r');
        if (initialSelectedTimeElement) {
            currentSelectedScheduleId = initialSelectedTimeElement.dataset.scheduleId;
        }

        // Fungsi untuk menyembunyikan/menampilkan pesan "tidak ada jadwal"
        function toggleNoScheduleMessage(show) {
            if (noScheduleMessage) {
                noScheduleMessage.style.display = show ? 'block' : 'none';
            } else {
                // Jika noScheduleMessage belum ada (misal, karena awalnya ada jadwal), buat elemen baru
                if (show) {
                    const p = document.createElement('p');
                    p.className = 'col-span-3 text-center text-gray-600';
                    p.textContent = 'Tidak ada jadwal tersedia untuk tanggal ini.';
                    p.id = 'no-schedule-message';
                    timeGrid.appendChild(p);
                    noScheduleMessage = p; // Perbarui referensi
                }
            }
        }

        // Fungsi untuk mengosongkan grid waktu (hanya slot waktu, bukan pesan "tidak ada jadwal")
        function clearTimeSlots() {
            Array.from(timeGrid.children).forEach(child => {
                if (child.id !== 'no-schedule-message') {
                    child.remove(); // Hapus elemen slot waktu
                }
            });
        }


    function updateUrl(date, time) {
        const doctorId = document.querySelector('.date-item').dataset.doctorId;
        let url = `{{ route('user.booking.show', ['doctor' => '__DOCTOR_ID__', 'patient' => '__PATIENT_ID__']) }}`;
        url = url.replace('__DOCTOR_ID__', doctorId);
        url = url.replace('__PATIENT_ID__', patientId);
        url += `?date=${date}`;
        if (time) {
            url += `&time=${encodeURIComponent(time)}`;
        }
        history.pushState({ date: date, time: time }, '', url);
    }

    async function fetchTimeSlots(date) {
        const doctorId = document.querySelector('.date-item').dataset.doctorId;
        const urlTemplate = `{{ route('user.booking.show', ['doctor' => '__DOCTOR_ID__', 'patient' => '__PATIENT_ID__']) }}`;

        let finalUrl = urlTemplate.replace('__DOCTOR_ID__', doctorId);
        finalUrl = finalUrl.replace('__PATIENT_ID__', patientId); 

        finalUrl += `?date=${date}`; 

            try {
                const response = await fetch(finalUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Penting untuk deteksi AJAX di Laravel
                    }
                });
                const data = await response.json();

                clearTimeSlots(); // Kosongkan slot waktu yang ada sebelum menampilkan yang baru

                // Cek jika ada slot yang tersedia dari respons backend
                if (data.times && data.times.length > 0) {
                    toggleNoScheduleMessage(false); // Sembunyikan pesan "tidak ada jadwal"
                    data.times.forEach(slot => {
                        const div = document.createElement('div');
                        let classes = 'time-item text-sm font-semibold text-center py-3 rounded-xl cursor-pointer transition-all duration-300';

                        // Tentukan kelas CSS jika slot ini adalah yang terpilih
                        if (currentSelectedTime === slot.time) {
                            classes += ' bg-gradient-to-r from-cyan-400 to-blue-600 text-white shadow-lg';
                            currentSelectedScheduleId = slot.schedule_id; // Set schedule_id jika ini waktu yang terpilih
                        } else {
                            classes += ' bg-blue-50 text-blue-900 hover:bg-blue-100';
                        }

                        div.className = classes;
                        div.dataset.time = slot.time;
                        div.dataset.scheduleId = slot.schedule_id; // Simpan schedule_id di dataset
                        div.textContent = slot.time;

                        // Tambahkan event listener untuk setiap slot waktu yang baru dibuat
                        div.addEventListener('click', function() {
                            // Hapus highlight dari slot waktu sebelumnya (jika ada)
                            const prevSelectedTimeElement = document.querySelector('.time-item.bg-gradient-to-r');
                            if (prevSelectedTimeElement) {
                                prevSelectedTimeElement.classList.remove('bg-gradient-to-r', 'from-cyan-400', 'to-blue-600', 'text-white', 'shadow-lg');
                                prevSelectedTimeElement.classList.add('bg-blue-50', 'text-blue-900', 'hover:bg-blue-100');
                            }

                            // Tambahkan highlight ke slot waktu yang baru diklik
                            this.classList.remove('bg-blue-50', 'text-blue-900', 'hover:bg-blue-100');
                            this.classList.add('bg-gradient-to-r', 'from-cyan-400', 'to-blue-600', 'text-white', 'shadow-lg');

                            currentSelectedTime = this.dataset.time;
                            // currentSelectedScheduleId = this.dataset.scheduleId; // Ambil schedule_id dari slot yang diklik
                            updateUrl(currentSelectedDate, currentSelectedTime); // Perbarui URL
                        });

                        timeGrid.appendChild(div); // Tambahkan div slot waktu ke grid
                    });
                } else {
                    toggleNoScheduleMessage(true); // Tampilkan pesan "tidak ada jadwal"
                }
            } catch (error) {
                console.error('Error fetching time slots:', error);
                clearTimeSlots(); // Pastikan slot waktu terhapus jika ada error
                toggleNoScheduleMessage(true); // Tampilkan pesan error
                if (noScheduleMessage) {
                    noScheduleMessage.textContent = 'Gagal memuat jadwal. Silakan coba lagi.';
                    noScheduleMessage.style.color = 'red';
                }
            }
        }

        // Tambahkan event listener untuk setiap item tanggal
        dateItems.forEach(item => {
            item.addEventListener('click', function() {
                const newDate = this.dataset.date;

                // Hapus highlight dari tanggal sebelumnya
                const prevSelectedDateElement = document.querySelector('.date-item.bg-gradient-to-b');
                if (prevSelectedDateElement) {
                    prevSelectedDateElement.classList.remove('bg-gradient-to-b', 'from-yellow-400', 'to-orange-500', 'text-white', 'shadow-lg');
                    prevSelectedDateElement.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
                }

                // Tambahkan highlight ke tanggal yang baru diklik
                this.classList.remove('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
                this.classList.add('bg-gradient-to-b', 'from-yellow-400', 'to-orange-500', 'text-white', 'shadow-lg');

                currentSelectedDate = newDate;
                currentSelectedTime = ''; // Reset waktu yang dipilih saat tanggal berubah
                currentSelectedScheduleId = ''; // Reset schedule_id saat tanggal berubah

                updateUrl(currentSelectedDate, currentSelectedTime); // Perbarui URL
                fetchTimeSlots(currentSelectedDate); // Ambil slot waktu baru untuk tanggal ini
            });
        });

        // Inisialisasi event listener untuk slot waktu yang dimuat secara statis saat halaman pertama kali dimuat
        document.querySelectorAll('.time-item').forEach(item => {
            item.addEventListener('click', function() {
                // Hapus highlight dari slot waktu sebelumnya
                const prevSelectedTimeElement = document.querySelector('.time-item.bg-gradient-to-r');
                if (prevSelectedTimeElement) {
                    prevSelectedTimeElement.classList.remove('bg-gradient-to-r', 'from-cyan-400', 'to-blue-600', 'text-white', 'shadow-lg');
                    prevSelectedTimeElement.classList.add('bg-blue-50', 'text-blue-900', 'hover:bg-blue-100');
                }

                // Tambahkan highlight ke slot waktu yang baru diklik
                this.classList.remove('bg-blue-50', 'text-blue-900', 'hover:bg-blue-100');
                this.classList.add('bg-gradient-to-r', 'from-cyan-400', 'to-blue-600', 'text-white', 'shadow-lg');

                currentSelectedTime = this.dataset.time;
                currentSelectedScheduleId = this.dataset.scheduleId;
                updateUrl(currentSelectedDate, currentSelectedTime);
            });
        });

        // Tangani tombol back/forward browser
        window.addEventListener('popstate', function(event) {
            if (event.state) {
                currentSelectedDate = event.state.date;
                currentSelectedTime = event.state.time;

                // Perbarui highlight tanggal
                dateItems.forEach(item => {
                    if (item.dataset.date === currentSelectedDate) {
                        item.classList.add('bg-gradient-to-b', 'from-yellow-400', 'to-orange-500', 'text-white', 'shadow-lg');
                        item.classList.remove('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
                    } else {
                        item.classList.remove('bg-gradient-to-b', 'from-yellow-400', 'to-orange-500', 'text-white', 'shadow-lg');
                        item.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
                    }
                });

                // Ambil ulang slot waktu untuk tanggal yang baru dari history
                fetchTimeSlots(currentSelectedDate);
            }
        });

        // Event listener untuk tombol Konfirmasi Booking
        confirmBookingBtn.addEventListener('click', function() {
            const doctorName = 'Dr. {{ $doctor->name ?? 'Dokter' }}'; // Nama dokter
            const bookingDate = currentSelectedDate;
            const bookingTime = currentSelectedTime;
            const scheduleId = currentSelectedScheduleId; // schedule_id yang akan dikirim ke backend

            // Validasi apakah tanggal, waktu, dan schedule_id sudah dipilih
            if (!bookingDate || !bookingTime) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Mohon pilih tanggal dan waktu booking terlebih dahulu.',
                    confirmButtonColor: '#3085d6',
                }); 
                return; // Hentikan proses jika belum lengkap
            }

            // Tampilkan konfirmasi SweetAlert
            Swal.fire({
                title: 'Konfirmasi Janji Temu Anda?',
                html: `
                    <p>Anda akan membuat janji temu dengan</p>
                    <p><strong>${doctorName}</strong> pada:</p>
                    <p>Tanggal: <strong>${new Date(bookingDate).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</strong></p>
                    <p>Pukul: <strong>${bookingTime}</strong></p>
                    <p>Pasien: <strong>${patientId ? '{{ $patient->name }}' : 'Tidak ada pasien yang dipilih'}</strong></p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Konfirmasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading sebelum mengirim permintaan
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Harap tunggu sebentar.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route('user.booking.store') }}', { // Pastikan rute ini benar
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN 
                        },
                        body: JSON.stringify({
                            patient_id: patientId, 
                            day_available_id: dayAvailableId,
                            date: bookingDate,
                            time: bookingTime,
                        })
                    })
                    .then(response => response.json()) // Parse respons JSON
                    .then(data => {
                        Swal.close(); // Tutup loading SweetAlert

                        if (data.success) {
                            Swal.fire(
                                'Berhasil!',
                                data.message, // Gunakan pesan sukses dari backend
                                'success'
                            ).then(() => {
                                // Redirect ke URL yang diberikan oleh backend
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                }
                            });
                        } else {
                            // Tampilkan pesan error dari backend
                            Swal.fire(
                                'Gagal!',
                                data.message || 'Terjadi kesalahan saat mengkonfirmasi janji temu.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.close(); // Tutup loading SweetAlert jika terjadi kesalahan
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan jaringan atau server. Silakan coba lagi.',
                            'error'
                        );
                    });
                }
            });
        });

        // Pengecekan awal untuk menampilkan/menyembunyikan pesan "tidak ada jadwal"
        // Berdasarkan data `$times` yang dimuat secara awal oleh Blade
        if ({{ count($times) }} === 0) {
            toggleNoScheduleMessage(true);
        } else {
            toggleNoScheduleMessage(false);
        }
    });
</script>
@endsection