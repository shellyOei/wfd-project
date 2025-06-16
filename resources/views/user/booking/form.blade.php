@extends('layout')

@section('content')
<div class="max-w-xl mx-auto p-4 min-h-screen font-sans antialiased pb-24">
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
        <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">
            {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('F Y') }}
        </h2>

        <div class="flex overflow-x-auto pb-4 custom-scrollbar" id="date-picker-container">
            @foreach ($bookingDates as $dateItem)
                <div class="flex-none w-20 mx-1 date-item
                            @if($selectedDate == $dateItem['full_date']) bg-gradient-to-b from-yellow-400 to-orange-500 text-white shadow-lg
                            @else text-gray-700 bg-gray-100 hover:bg-gray-200 @endif
                            rounded-lg py-2 text-center cursor-pointer transition-all duration-300"
                    data-date="{{ $dateItem['full_date'] }}"
                    data-doctor-id="{{ $doctor->id }}"
                >
                    <div class="text-lg font-bold">{{ $dateItem['day_name'] }}</div>
                    <div class="text-sm">{{ $dateItem['date_num'] }}</div>
                </div>
            @endforeach
        </div>

        <p class="text-sm text-gray-500 mt-4 text-center">
            <strong class="text-blue-600">Info:</strong> Booking tersedia hingga 7 hari ke depan.
        </p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-md mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Waktu yang Tersedia</h2>
        <div class="grid grid-cols-3 gap-3" id="time-slots-grid">
            @forelse ($times as $time)
                <div class="time-item
                            @if(isset($selectedTime) && $selectedTime == $time) bg-gradient-to-r from-cyan-400 to-blue-600 text-white shadow-lg
                            @else bg-blue-50 text-blue-900 hover:bg-blue-100 @endif
                            text-sm font-semibold text-center py-3 rounded-xl cursor-pointer transition-all duration-300"
                    data-time="{{ $time }}"
                >
                    {{ $time }}
                </div>
            @empty
                <p class="col-span-3 text-center text-gray-600" id="no-schedule-message">Tidak ada jadwal tersedia untuk tanggal ini.</p>
            @endforelse
        </div>
    </div>

    <button id="confirmBookingBtn" class="w-full bg-gradient-to-r from-cyan-400 to-blue-600 text-white font-bold py-3 rounded-xl shadow-md hover:opacity-90 transition">
        Konfirmasi Booking
    </button>
</div>

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
</style>
@endsection

@section('script')
<script>
 document.addEventListener('DOMContentLoaded', function() {
    const dateItems = document.querySelectorAll('.date-item');
    const timeGrid = document.getElementById('time-slots-grid');
    // Pastikan referensi ke pesan "tidak ada jadwal" diambil dari elemen yang sudah ada
    let noScheduleMessage = document.getElementById('no-schedule-message');

    const confirmBookingBtn = document.getElementById('confirmBookingBtn');

    let currentSelectedDate = '{{ $selectedDate }}';
    let currentSelectedTime = '{{ $selectedTime ?? '' }}';

    // Fungsi untuk menyembunyikan/menampilkan pesan "tidak ada jadwal"
    function toggleNoScheduleMessage(show) {
        if (noScheduleMessage) {
            noScheduleMessage.style.display = show ? 'block' : 'none';
        }
    }

    // Fungsi untuk mengosongkan grid waktu (hanya slot waktu, bukan pesan)
    function clearTimeSlots() {
        // Hapus semua child dari timeGrid kecuali noScheduleMessage
        Array.from(timeGrid.children).forEach(child => {
            if (child.id !== 'no-schedule-message') {
                timeGrid.removeChild(child);
            }
        });
    }


    function updateUrl(date, time) {
        const doctorId = document.querySelector('.date-item').dataset.doctorId;
        let url = `{{ route('user.booking.show', ['doctor' => '__DOCTOR_ID__']) }}`;
        url = url.replace('__DOCTOR_ID__', doctorId);
        url += `?date=${date}`;
        if (time) {
            url += `&time=${encodeURIComponent(time)}`;
        }
        history.pushState({ date: date, time: time }, '', url);
    }

    async function fetchTimeSlots(date) {
        const doctorId = document.querySelector('.date-item').dataset.doctorId;
        const url = `{{ route('user.booking.show', ['doctor' => '__DOCTOR_ID__']) }}?date=${date}`;
        const finalUrl = url.replace('__DOCTOR_ID__', doctorId);

        try {
            const response = await fetch(finalUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();

            clearTimeSlots(); // Kosongkan hanya slot waktu

            if (data.times.length > 0) {
                toggleNoScheduleMessage(false); // Sembunyikan pesan
                data.times.forEach(time => {
                    const div = document.createElement('div');
                    let classes = 'time-item text-sm font-semibold text-center py-3 rounded-xl cursor-pointer transition-all duration-300';

                    if (currentSelectedTime === time) {
                        classes += ' bg-gradient-to-r from-cyan-400 to-blue-600 text-white shadow-lg';
                    } else {
                        classes += ' bg-blue-50 text-blue-900 hover:bg-blue-100';
                    }

                    div.className = classes;
                    div.dataset.time = time;
                    div.textContent = time;

                    div.addEventListener('click', function() {
                        const prevSelectedTime = document.querySelector('.time-item.bg-gradient-to-r');
                        if (prevSelectedTime) {
                            prevSelectedTime.classList.remove('bg-gradient-to-r', 'from-cyan-400', 'to-blue-600', 'text-white', 'shadow-lg');
                            prevSelectedTime.classList.add('bg-blue-50', 'text-blue-900', 'hover:bg-blue-100');
                        }

                        this.classList.remove('bg-blue-50', 'text-blue-900', 'hover:bg-blue-100');
                        this.classList.add('bg-gradient-to-r', 'from-cyan-400', 'to-blue-600', 'text-white', 'shadow-lg');

                        currentSelectedTime = time;
                        updateUrl(currentSelectedDate, currentSelectedTime);
                    });

                    timeGrid.appendChild(div);
                });
            } else {
                toggleNoScheduleMessage(true); // Tampilkan pesan
            }
        } catch (error) {
            console.error('Error fetching time slots:', error);
            clearTimeSlots(); // Pastikan slot waktu terhapus
            toggleNoScheduleMessage(true); // Tampilkan pesan error jika terjadi masalah
            // Optional: Anda bisa menambahkan pesan error yang berbeda di sini
            // if (noScheduleMessage) {
            //     noScheduleMessage.textContent = 'Gagal memuat jadwal. Silakan coba lagi.';
            //     noScheduleMessage.style.color = 'red';
            // }
        }
    }

    // Add click listeners to date items
    dateItems.forEach(item => {
        item.addEventListener('click', function() {
            const newDate = this.dataset.date;

            const prevSelectedDateElement = document.querySelector('.date-item.bg-gradient-to-b');
            if (prevSelectedDateElement) {
                prevSelectedDateElement.classList.remove('bg-gradient-to-b', 'from-yellow-400', 'to-orange-500', 'text-white', 'shadow-lg');
                prevSelectedDateElement.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
            }

            this.classList.remove('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
            this.classList.add('bg-gradient-to-b', 'from-yellow-400', 'to-orange-500', 'text-white', 'shadow-lg');

            currentSelectedDate = newDate;
            currentSelectedTime = ''; // Reset selected time when date changes

            updateUrl(currentSelectedDate, currentSelectedTime);
            fetchTimeSlots(currentSelectedDate);
        });
    });

    // Initialize time slot click listeners for the initial render (if any times are present)
    // Make sure this runs *after* noScheduleMessage is properly set up
    document.querySelectorAll('.time-item').forEach(item => {
        item.addEventListener('click', function() {
            const prevSelectedTime = document.querySelector('.time-item.bg-gradient-to-r');
            if (prevSelectedTime) {
                prevSelectedTime.classList.remove('bg-gradient-to-r', 'from-cyan-400', 'to-blue-600', 'text-white', 'shadow-lg');
                prevSelectedTime.classList.add('bg-blue-50', 'text-blue-900', 'hover:bg-blue-100');
            }

            this.classList.remove('bg-blue-50', 'text-blue-900', 'hover:bg-blue-100');
            this.classList.add('bg-gradient-to-r', 'from-cyan-400', 'to-blue-600', 'text-white', 'shadow-lg');

            currentSelectedTime = this.dataset.time;
            updateUrl(currentSelectedDate, currentSelectedTime);
        });
    });

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        if (event.state) {
            currentSelectedDate = event.state.date;
            currentSelectedTime = event.state.time;

            // Update date highlight
            dateItems.forEach(item => {
                if (item.dataset.date === currentSelectedDate) {
                    item.classList.add('bg-gradient-to-b', 'from-yellow-400', 'to-orange-500', 'text-white', 'shadow-lg');
                    item.classList.remove('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
                } else {
                    item.classList.remove('bg-gradient-to-b', 'from-yellow-400', 'to-orange-500', 'text-white', 'shadow-lg');
                    item.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
                }
            });

            fetchTimeSlots(currentSelectedDate); // Re-fetch times for the new date
        }
    });

    // --- NEW: SweetAlert for Konfirmasi Booking button ---
    confirmBookingBtn.addEventListener('click', function() {
        const doctorName = 'Dr. {{ $doctor->name }}'; // Assuming doctor name is available in blade
        const bookingDate = currentSelectedDate;
        const bookingTime = currentSelectedTime;

        if (!bookingDate || !bookingTime) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Mohon pilih tanggal dan waktu booking terlebih dahulu.',
                confirmButtonColor: '#3085d6',
            });
            return; // Stop if date or time is not selected
        }

        Swal.fire({
            title: 'Konfirmasi Booking Anda?',
            html: `
                <p>Anda akan membuat janji temu dengan</p>
                <p><strong>${doctorName}</strong> pada:</p>
                <p>Tanggal: <strong>${new Date(bookingDate).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</strong></p>
                <p>Pukul: <strong>${bookingTime}</strong></p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Konfirmasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Here you would typically send an AJAX request to your backend
                // to actually create the booking in the database.
                // For now, we'll just show a success message.

                // Example: Send data to a backend route
                // const doctorId = document.querySelector('.date-item').dataset.doctorId;
                // fetch('/api/book-appointment', { // Replace with your actual booking API route
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': '{{ csrf_token() }}' // For Laravel POST requests
                //     },
                //     body: JSON.stringify({
                //         doctor_id: doctorId,
                //         date: bookingDate,
                //         time: bookingTime
                //     })
                // })
                // .then(response => response.json())
                // .then(data => {
                //     if (data.success) {
                //         Swal.fire(
                //             'Berhasil!',
                //             'Janji temu Anda telah berhasil dikonfirmasi.',
                //             'success'
                //         );
                //         // Optionally, redirect to a success page or user dashboard
                //         // window.location.href = '/booking/success';
                //     } else {
                //         Swal.fire(
                //             'Gagal!',
                //             data.message || 'Terjadi kesalahan saat mengkonfirmasi janji temu.',
                //             'error'
                //         );
                //     }
                // })
                // .catch(error => {
                //     console.error('Error:', error);
                //     Swal.fire(
                //         'Error!',
                //         'Terjadi kesalahan jaringan atau server.',
                //         'error'
                //     );
                // });

                // For demonstration, just show success alert immediately:
                Swal.fire(
                    'Berhasil!',
                    'Janji temu Anda telah berhasil dikonfirmasi.',
                    'success'
                );
                // You might want to redirect the user after success, e.g.:
                // setTimeout(() => {
                //     window.location.href = '/user/my-appointments'; // Redirect to user's appointments
                // }, 2000);
            }
        });
    });

    // Initial check to show/hide message based on initial data
    if (document.querySelectorAll('.time-item').length === 0) {
        toggleNoScheduleMessage(true);
    } else {
        toggleNoScheduleMessage(false);
    }
});
</script>
@endsection