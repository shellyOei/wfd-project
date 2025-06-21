@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 min-h-screen font-sans antialiased pb-24">
    <div class="flex items-center justify-between mb-6">
        <button onclick="history.back()" class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out p-1 -ml-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex-grow text-center">Dokter</h1>
        <div class="w-7"></div> 
    </div>

    <div class="mb-6 relative">
        <input
            type="text"
            id="doctorSearchInput"
            placeholder="Temukan Dokter..."
            class="w-full pl-10 pr-4 py-3 rounded-2xl bg-white border border-gray-200 placeholder-gray-400 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-300 ease-in-out shadow-sm"
            autocomplete="off"
        />
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        {{-- container search --}}
        <div id="suggestionsBox" class="absolute z-10 w-full bg-white border border-gray-200 rounded-xl shadow-lg mt-2 hidden max-h-60 overflow-y-auto">
        </div>
    </div>

    <h2 class="text-xl md:text-2xl font-semibold text-gray-700 mb-4 max-sm:px-2">Pilih Spesialis</h2>

    <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 max-sm:px-5 gap-4 sm:gap-4">
        @foreach ($specializations as $specialization)
        <a href="{{ route('doctors.by_specialization', $specialization) }}" class="flex flex-col items-center justify-center p-2 sm:p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out cursor-pointer border border-gray-100 transform hover:-translate-y-1">
            <img src="{{ asset($specialization->icon) }}" alt="{{ $specialization->name }} Icon" class="w-10 h-10 sm:w-12 sm:h-12 mb-2">
            <span class="text-xs sm:text-sm text-blue-700 font-semibold text-center mt-1">{{ $specialization->name }}</span>
        </a>
        @endforeach
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('doctorSearchInput');
        const suggestionsBox = document.getElementById('suggestionsBox');

        let debounceTimeout;
        const debounceDelay = 300; // milliseconds

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimeout);
            const query = this.value.trim();

            if (query.length === 0) {
                suggestionsBox.classList.add('hidden'); // Hide if input is empty
                return;
            }

            debounceTimeout = setTimeout(() => {
                fetchSuggestions(query);
            }, debounceDelay);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
                suggestionsBox.classList.add('hidden');
            }
        });

        // Function to fetch suggestions from the backend
        async function fetchSuggestions(query) {
            try {
                const response = await fetch(`{{ route('doctor_suggestions') }}?query=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const doctors = await response.json();
                renderSuggestions(doctors);
            } catch (error) {
                console.error('Error fetching doctor suggestions:', error);
                suggestionsBox.classList.add('hidden'); // Hide box on error
            }
        }

        // Function to render suggestions in the box
        function renderSuggestions(doctors) {
            suggestionsBox.innerHTML = ''; // Clear previous suggestions

            if (doctors.length === 0) {
                const noResultDiv = document.createElement('div');
                noResultDiv.className = 'p-3 text-gray-500 text-sm';
                noResultDiv.textContent = 'Tidak ada dokter yang ditemukan.';
                suggestionsBox.appendChild(noResultDiv);
                suggestionsBox.classList.remove('hidden');
                return;
            }

            doctors.forEach(doctor => {
                const suggestionItem = document.createElement('a');
                // Use a proper Laravel route helper here if possible, otherwise keep it as is
                suggestionItem.href = `{{ url('doctors') }}/${doctor.id}`; // Ensure this URL is correct for your doctor detail page
                suggestionItem.className = 'flex items-center p-3 hover:bg-blue-50 cursor-pointer transition-colors duration-200 block';

                const doctorName = `${doctor.front_title ? doctor.front_title + ' ' : ''}${doctor.name}${doctor.back_title ? ' ' + doctor.back_title : ''}`;
                const specializationName = doctor.specialization ? doctor.specialization.name : 'Spesialisasi Tidak Diketahui';

                suggestionItem.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <div>
                        <span class="font-semibold text-gray-800 block">${doctorName}</span>
                        <span class="text-sm text-gray-600 block">${specializationName}</span>
                    </div>
                `;
                suggestionsBox.appendChild(suggestionItem);
            });

            suggestionsBox.classList.remove('hidden');
        }
    });
</script>
@endsection

@push('script')
<script>
    highlightActiveMenu('book');
</script>
@endpush