@extends('layout')

@section('head')

@endsection

@section('content')
{{-- CHANGES MADE:
    1. Main Container: Changed `max-w-md` to `max-w-4xl` (or higher) to allow content to expand on larger screens.
       Adjusted responsive padding (`p-4 sm:p-6 lg:p-8`) for better spacing across devices.
    2. Headings: Increased font size on medium screens and up (`md:text-3xl`, `md:text-2xl`) for better hierarchy.
    3. Doctor Cards: Adjusted padding for better spacing within cards on small screens (`p-3 sm:p-4`).
       Ensured consistent sizing for images and text within cards for responsiveness.
--}}
<div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 min-h-screen font-sans antialiased pb-24">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out p-1 -ml-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex-grow text-center">
            Daftar Dokter
            @isset($specialization)
                <br><span class="text-xl md:text-2xl text-blue-600">{{ $specialization->name }}</span>
            @endisset
        </h1>
        <div class="w-7"></div>
    </div>

    <div class="mb-6">
        <div class="relative">
            <input
                type="text"
                id="searchInput"
                name="search"
                placeholder="Temukan Dokter..."
                value="{{ request('search') }}"
                class="w-full pl-10 pr-4 py-3 rounded-2xl bg-white border border-gray-200 placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-300 ease-in-out shadow-sm"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            @if(isset($specialization))
                <input type="hidden" id="specializationId" value="{{ $specialization->id }}">
            @endif
        </div>
    </div>

    <h2 class="text-xl md:text-2xl font-semibold text-gray-700 mb-4 max-sm:px-2">List Dokter</h2>

    <div class="space-y-4 max-sm:px-5" id="doctorListContainer">
        @forelse ($doctors as $doctor)
            <a href="{{ route('doctors.show', $doctor->id) }}" class="block">
                <div class="flex items-center p-3 sm:p-4 rounded-2xl text-white shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1"
                    style="background: linear-gradient(to right, #40ACD8, #4980FF, #2244C2);">
                    <img src="{{ asset($doctor->photo ?? 'appointment/doctor_placeholder.png') }}" alt="Dr. {{ $doctor->name }}"
                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-white mr-4 flex-shrink-0" />
                    <div class="flex-1 overflow-hidden">
                        <h3 class="text-base sm:text-lg font-bold truncate">{{ $doctor->front_title }} {{ $doctor->name }} {{ $doctor->back_title }}</h3>
                        <p class="text-xs sm:text-sm truncate opacity-90">
                            {{ $doctor->specialization->name ?? 'Tidak Ada Spesialisasi' }}
                        </p>
                        <p class="text-xs mt-1 line-clamp-2 opacity-80">
                            {{ $doctor->description ?? 'Tidak ada deskripsi.' }}
                        </p>
                        <button class="mt-3 bg-white text-blue-600 font-semibold px-4 py-2 rounded-full text-xs sm:text-sm hover:bg-gray-100 transition duration-300 ease-in-out shadow-sm">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-center text-gray-600 text-lg py-8" id="noDoctorsMessage">Tidak ada dokter yang ditemukan.</p>
        @endforelse
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const doctorListContainer = document.getElementById('doctorListContainer');
        const specializationIdInput = document.getElementById('specializationId');
        const noDoctorsMessage = document.getElementById('noDoctorsMessage');

        let typingTimer; // Timer for debounce input
        const doneTypingInterval = 300; // Time in milliseconds after typing stops to trigger search

        // Function to render doctor list
        function renderDoctors(doctors) {
            doctorListContainer.innerHTML = ''; // Clear current container

            if (doctors.length === 0) {
                // Display "no doctors" message if no results
                const p = document.createElement('p');
                p.className = 'text-center text-gray-600 text-lg py-8';
                p.textContent = 'Tidak ada dokter yang ditemukan.';
                doctorListContainer.appendChild(p);
            } else {
                // Hide "no doctors" message if there are results (if previously shown)
                if (noDoctorsMessage) {
                    noDoctorsMessage.style.display = 'none';
                }

                doctors.forEach(doctor => {
                    const doctorCard = `
                        <a href="/doctors/${doctor.id}" class="block">
                            <div class="flex items-center p-3 sm:p-4 rounded-2xl text-white shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1"
                                style="background: linear-gradient(to right, #40ACD8, #4980FF, #2244C2);">
                                <img src="${doctor.photo ? '/'+doctor.photo : '/appointment/doctor_placeholder.png'}" alt="Dr. ${doctor.name}"
                                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-white mr-4 flex-shrink-0" />
                                <div class="flex-1 overflow-hidden">
                                    <h3 class="text-base sm:text-lg font-bold truncate">${doctor.front_title ? doctor.front_title + ' ' : ''}${doctor.name}${doctor.back_title ? ' ' + doctor.back_title : ''}</h3>
                                    <p class="text-xs sm:text-sm truncate opacity-90">
                                        ${doctor.specialization ? doctor.specialization.name : 'Tidak Ada Spesialisasi'}
                                    </p>
                                    <p class="text-xs mt-1 line-clamp-2 opacity-80">
                                        ${doctor.description || 'Tidak ada deskripsi.'}
                                    </p>
                                    <button class="mt-3 bg-white text-blue-600 font-semibold px-4 py-2 rounded-full text-xs sm:text-sm hover:bg-gray-100 transition duration-300 ease-in-out shadow-sm">
                                        Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </a>
                    `;
                    doctorListContainer.insertAdjacentHTML('beforeend', doctorCard);
                });
            }
        }

        // Function to perform AJAX search
        async function performSearch() {
            const query = searchInput.value;
            const specializationId = specializationIdInput ? specializationIdInput.value : '';

            let url = `{{ route('doctors.search') }}?query=${encodeURIComponent(query)}`;

            if (specializationId) {
                url += `&specialization_id=${encodeURIComponent(specializationId)}`;
            }

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const doctors = await response.json();
                renderDoctors(doctors);
            } catch (error) {
                console.error('Error fetching doctors:', error);
                doctorListContainer.innerHTML = '<p class="text-center text-red-500 text-lg py-8">Terjadi kesalahan saat memuat dokter.</p>';
            }
        }

        // Event listener for search input (with debounce)
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSearch, doneTypingInterval);
        });

        searchInput.addEventListener('keydown', function() {
            clearTimeout(typingTimer);
        });

        // Trigger initial search if there's a value in the input (e.g., from a previous request)
        if (searchInput.value.length > 0) {
            performSearch();
        }
    });
</script>
@endsection

@push('script')
<script>
    highlightActiveMenu('book');
</script>
@endpush