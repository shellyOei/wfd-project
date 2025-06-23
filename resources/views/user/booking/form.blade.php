@extends('layout')

@section('content')
<div class="max-w-xl mx-auto p-4 min-h-screen antialiased pb-24">
    {{-- BAGIAN HEADER DAN PEMILIH TANGGAL (TIDAK ADA PERUBAHAN) --}}
    <div class="flex items-center mb-6 mt-4">
        <a href="{{ url()->previous() }}" class="mr-2 text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-xl font-bold flex-grow text-center text-gray-800">Pilih Tanggal & Waktu</h1>
        <div class="w-6"></div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-md mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 text-center" id="month-year-header">
            {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('F Y') }}
        </h2>

        <div class="flex justify-center">
            <div class="flex overflow-x-auto pb-4 custom-scrollbar" id="date-picker-container">
                @foreach ($bookingDates as $dateItem)
                    <div class="flex-none w-20 mx-1 date-item
                                @if($selectedDate == $dateItem['full_date']) bg-gradient-to-b from-yellow-400 to-orange-500 text-white shadow-lg
                                @else text-gray-700 bg-gray-100 hover:bg-gray-200 @endif
                                rounded-lg py-2 cursor-pointer transition-all duration-300
                                flex flex-col justify-center items-center h-full"
                         data-date="{{ $dateItem['full_date'] }}">
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

    {{-- BAGIAN SLOT WAKTU (TIDAK ADA PERUBAHAN DI HTML) --}}
    <div class="bg-white p-4 rounded-xl shadow-md mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Waktu yang Tersedia</h2>
        <div class="grid grid-cols-3 gap-3" id="time-slots-grid">
            {{-- Loop awal ini akan digantikan oleh JavaScript saat halaman dimuat --}}
            @forelse ($times as $slot)
                <div class="time-item text-sm font-semibold text-center py-3 rounded-xl
                    @if($slot['isBooked'])
                        bg-gray-200 text-gray-400 cursor-not-allowed line-through
                    @elseif(isset($selectedTime) && $selectedTime == $slot['time'])
                        bg-gradient-to-r from-cyan-400 to-blue-600 text-white shadow-lg cursor-pointer
                    @else
                        bg-blue-50 text-blue-900 hover:bg-blue-100 cursor-pointer
                    @endif"
                    data-time="{{ $slot['time'] }}">
                    {{ $slot['time'] }}
                </div>
            @empty
                <p class="col-span-3 text-center text-gray-600">Tidak ada jadwal tersedia untuk tanggal ini.</p>
            @endforelse
        </div>
    </div>

    {{-- BAGIAN TOMBOL BOOKING (TIDAK ADA PERUBAHAN) --}}
    <button id="confirmBookingBtn" class="w-full bg-gradient-to-r from-cyan-400 to-blue-600 text-white font-bold py-3 rounded-xl shadow-md hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed">
        Konfirmasi Booking
    </button>
</div>

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    highlightActiveMenu('book');

    // === KONSTANTA & VARIABEL ===
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const DOCTOR_ID = '{{ $doctor->id }}';
    const PATIENT_ID = '{{ $patient->id }}';
    const PATIENT_NAME = '{{ addslashes($patient->name) }}';
    const DOCTOR_NAME = '{{ addslashes($doctor->name) }}';

    const datePickerContainer = document.getElementById('date-picker-container');
    const timeGrid = document.getElementById('time-slots-grid');
    const confirmBookingBtn = document.getElementById('confirmBookingBtn');
    const monthYearHeader = document.getElementById('month-year-header');
    
    let currentSelectedDate = '{{ $selectedDate }}';
    let currentSelectedTime = '{{ $selectedTime ?? "" }}';
    let dayAvailableIdForBooking = '{{ $dayAvailable->id ?? "" }}';

    // === FUNGSI-FUNGSI ===

    /**
     * Mengaktifkan/menonaktifkan tombol booking.
     */
    function updateBookingButtonState() {
        confirmBookingBtn.disabled = !(currentSelectedDate && currentSelectedTime && dayAvailableIdForBooking);
    }

    /**
     * Merender ulang slot waktu di grid.
     */
    function renderTimeSlots(slots) {
        timeGrid.innerHTML = ''; 

        if (!slots || slots.length === 0) {
            timeGrid.innerHTML = '<p class="col-span-3 text-center text-gray-600">Tidak ada jadwal tersedia untuk tanggal ini.</p>';
            return;
        }
        
        const todayString = new Date().toISOString().split('T')[0];

        slots.forEach(slot => {
            const div = document.createElement('div');
            let classes = 'time-item text-sm font-semibold text-center py-3 rounded-xl transition-all duration-300';
            
            let isPast = false;
            if (currentSelectedDate === todayString) {
                const now = new Date();
                const slotDateTime = new Date(`${currentSelectedDate}T${slot.time}`);
                if (slotDateTime < now) {
                    isPast = true;
                }
            }

            if (slot.isBooked || isPast) {
                classes += ' bg-gray-200 text-gray-400 cursor-not-allowed line-through';
            } else {
                classes += ' cursor-pointer';
                if (currentSelectedTime === slot.time) {
                    classes += ' bg-gradient-to-r from-cyan-400 to-blue-600 text-white shadow-lg';
                } else {
                    classes += ' bg-blue-50 text-blue-900 hover:bg-blue-100';
                }
                div.addEventListener('click', () => handleTimeSelection(div));
            }

            div.className = classes;
            div.dataset.time = slot.time;
            div.textContent = slot.time;
            timeGrid.appendChild(div);
        });
    }

    /**
     * Mengambil data slot waktu dari server.
     */
    async function fetchAndRenderTimeSlots(date) {
        const url = `{{ route('user.booking.show', ['doctor' => $doctor->id, 'patient' => $patient->id]) }}?date=${date}`;
        
        try {
            timeGrid.innerHTML = '<p class="col-span-3 text-center text-gray-500">Memuat jadwal...</p>';
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Network response was not ok.');
            const data = await response.json();
            
            dayAvailableIdForBooking = data.day_available_id; 
            
            const newHeaderDate = new Date(date + 'T00:00:00Z');
            monthYearHeader.textContent = newHeaderDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            
            renderTimeSlots(data.times);
        } catch (error) {
            console.error('Error fetching time slots:', error);
            timeGrid.innerHTML = '<p class="col-span-3 text-center text-red-600">Gagal memuat jadwal. Silakan coba lagi.</p>';
        }
    }
    
    /**
     * Menangani logika saat slot waktu dipilih.
     */
    function handleTimeSelection(element) {
        const prevSelected = timeGrid.querySelector('.bg-gradient-to-r');
        if (prevSelected) {
            prevSelected.className = prevSelected.className.replace('bg-gradient-to-r from-cyan-400 to-blue-600 text-white shadow-lg', 'bg-blue-50 text-blue-900 hover:bg-blue-100');
        }

        element.className = element.className.replace('bg-blue-50 text-blue-900 hover:bg-blue-100', 'bg-gradient-to-r from-cyan-400 to-blue-600 text-white shadow-lg');
        
        currentSelectedTime = element.dataset.time;
        updateBookingButtonState();
        updateUrl(currentSelectedDate, currentSelectedTime);
    }
    
    /**
     * Memperbarui URL di browser.
     */
    function updateUrl(date, time) {
        const url = new URL(window.location);
        url.searchParams.set('date', date);
        if (time) {
            url.searchParams.set('time', time);
        } else {
            url.searchParams.delete('time');
        }
        history.pushState({ date, time }, '', url);
    }

    // === EVENT LISTENERS ===

    datePickerContainer.addEventListener('click', function(e) {
        const target = e.target.closest('.date-item');
        if (!target || target.dataset.date === currentSelectedDate) return;

        const newDate = target.dataset.date;
        const prevSelected = datePickerContainer.querySelector('.bg-gradient-to-b');
        if (prevSelected) {
            prevSelected.className = prevSelected.className.replace(/bg-gradient-to-b from-yellow-400 to-orange-500 text-white shadow-lg/g, 'text-gray-700 bg-gray-100 hover:bg-gray-200');
        }
        target.className = target.className.replace(/text-gray-700 bg-gray-100 hover:bg-gray-200/g, 'bg-gradient-to-b from-yellow-400 to-orange-500 text-white shadow-lg');

        currentSelectedDate = newDate;
        currentSelectedTime = ''; 
        dayAvailableIdForBooking = '';
        updateBookingButtonState();
        updateUrl(currentSelectedDate, null);
        fetchAndRenderTimeSlots(currentSelectedDate);
    });

    timeGrid.addEventListener('click', function(e) {
        const target = e.target.closest('.time-item');
        if (target && !target.classList.contains('cursor-not-allowed')) {
            handleTimeSelection(target);
        }
    });
    
    confirmBookingBtn.addEventListener('click', function() {
        if (this.disabled) return;

        // [FIXED] Definisikan variabel sebelum digunakan
        const bookingDate = currentSelectedDate;
        const bookingTime = currentSelectedTime;

        // [FIXED] Kesalahan sintaks titik koma (;) dihapus, .then() disambungkan
        Swal.fire({
            title: 'Konfirmasi Janji Temu Anda?',
            html: `<p>Anda akan membuat janji temu dengan</p>
                   <p><strong>${DOCTOR_NAME}</strong> pada:</p>
                   <p>Tanggal: <strong>${new Date(bookingDate).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</strong></p>
                   <p>Pukul: <strong>${bookingTime}</strong></p>
                   <p>Pasien: <strong>${PATIENT_NAME}</strong></p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Konfirmasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                fetch('{{ route('user.booking.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        patient_id: PATIENT_ID,
                        day_available_id: dayAvailableIdForBooking,
                        date: bookingDate,
                        time: bookingTime,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => {
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            }
                        });
                    } else {
                        Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Terjadi kesalahan jaringan atau server.', 'error');
                });
            }
        });
    });
    
    // === INISIALISASI HALAMAN ===
    updateBookingButtonState();
});
</script>
@endsection
