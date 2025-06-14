@extends('layout')

@section('head')
<style>
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        textarea,
        select {
            @apply w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent;
        }
        textarea {
            padding-left: 1rem; /* Adjust padding for textarea if no icon is present */
        }
        /* Stepper styles */
        .stepper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
            width: 85%;
        }
        .stepper-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
        }
        .stepper-item:not(:last-child)::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: #D1D5DB; /* light gray */
            left: 50%;
            top: 15px; /* Adjust to align with circle center */
            transform: translateX(0%);
            z-index: -1;
        }
        .stepper-item.completed::after {
            background-color: var(--blue1); /* Blue when completed */
        }
        .stepper-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #D1D5DB; /* light gray */
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            z-index: 10;
        }
        .stepper-circle.active {
            background-color: var(--blue1);
        }
        .stepper-circle.completed {
            background-color: var(--blue1);
        }
        .stepper-label {
            margin-top: 0.5rem;
            font-size: 0.875rem; /* text-sm */
            color: #6B7280; /* gray-500 */
        }
        .stepper-label.active {
            color: var(--blue1);
        }
        .stepper-label.completed {
            color: var(--blue1);
        }
</style>
@endsection

@section('content')
    <div class="flex flex-col h-screen w-screen overflow-x-hidden items-center pt-5 pb-20 text-black">
        {{-- logo --}}
        <img class="w-[35%]" src="{{ asset('assets/ewaps-logo.png')}}" alt="">

        <div class="stepper">
            <div class="stepper-item" id="stepper-step-1">
                <div class="stepper-circle active" id="circle-1">1</div>
                <div class="stepper-label active" id="label-1">Informasi Pribadi</div>
            </div>
            <div class="stepper-item" id="stepper-step-2">
                <div class="stepper-circle" id="circle-2">2</div>
                <div class="stepper-label" id="label-2">Detail Medis & Lainnya</div>
            </div>
        </div>

        <form class="w-[85%] max-w-md space-y-4" action="{{ Route('register.patient.post')}}" method="POST" id="patientRegistrationForm">
            @csrf
            <div id="error-container" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">Terdapat masalah dengan input Anda.</span>
                <ul id="error-list" class="mt-3 list-disc list-inside">
                    <!-- Errors will be populated here by JavaScript for demonstration or by Laravel for real app -->
                </ul>
            </div>

            <!-- Step 1-->
            <div id="step-1-content" class="form-section">
                <h2 class="text-xl font-bold mb-4 text-center">Langkah 1: Informasi Pribadi</h2>

                <!-- Nama Lengkap Input Field -->
                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="name">Nama Lengkap</label>
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Contoh: Budi Santoso"
                            name="name" id="name" value="" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Nomor Telepon Input Field -->
                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="phone">Nomor Telepon</label>
                    <div class="relative">
                        <input
                            type="tel"
                            placeholder="Contoh: 081234567890"
                            name="phone" id="phone" value="" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-phone text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Jenis Kelamin Dropdown -->
                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="sex">Jenis Kelamin</label>
                    <div class="relative">
                        <select name="sex" id="sex" required
                                class="block appearance-none w-full bg-white border border-2 border-[var(--blue1)] text-gray-700 py-3 pl-10 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-[var(--blue1)]">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Male">Laki-laki</option>
                            <option value="Female">Perempuan</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-venus-mars text-gray-400"></i>
                        </div>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Tanggal Lahir Input Field -->
                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="date_of_birth">Tanggal Lahir</label>
                    <div class="relative">
                        <input
                            type="date"
                            name="date_of_birth" id="date_of_birth" value="" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-calendar-days text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Nomor KTP Input Field -->
                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="id_card_number">Nomor KTP</label>
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Contoh: 1234567890123456"
                            name="id_card_number" id="id_card_number" value="" pattern="[0-9]{16}" title="Nomor KTP harus 16 digit angka" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-id-card text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Profesi Input Field -->
                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="occupation">Profesi</label>
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Contoh: Karyawan Swasta"
                            name="occupation" id="occupation" value="" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-briefcase text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Alamat Tempat Tinggal Textarea -->
                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="address">Alamat Tempat Tinggal</label>
                    <div class="relative">
                        <textarea
                            placeholder="Contoh: Jl. Merdeka No. 10, Jakarta"
                            name="address" id="address" rows="3" required
                            class="w-full pl-4 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent"></textarea>
                    </div>
                </div>

                <button type="button" id="next-step-btn" class="btn text-center w-full py-3 rounded-lg text-white font-semibold shadow-lg
                    bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)]
                    hover:from-purple-600 hover:to-indigo-700
                    hover:shadow-xl transition-all duration-300 ease-in-out
                    focus:outline-none focus:ring-2 focus:ring-[var(--blueGradient1)] focus:ring-opacity-75 font-bold tracking-wide">
                    Selanjutnya
                </button>
            </div>

            <!-- Step 2: Detail Medis & Lainnya -->
            <div id="step-2-content" class="form-section hidden">
                <h2 class="text-xl font-bold mb-4 text-center">Langkah 2: Detail Medis & Lainnya</h2>

                <!-- Golongan Darah Dropdown -->
                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="blood_type">Golongan Darah</label>
                    <div class="relative">
                        <select name="blood_type" id="blood_type" required
                                class="block appearance-none w-full bg-white border border-2 border-[var(--blue1)] text-gray-700 py-3 pl-10 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-[var(--blue1)]">
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-droplet text-gray-400"></i>
                        </div>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Rhesus Dropdown -->
                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="rhesus_factor">Rhesus</label>
                    <div class="relative">
                        <select name="rhesus_factor" id="rhesus_factor" required
                                class="block appearance-none w-full bg-white border border-2 border-[var(--blue1)] text-gray-700 py-3 pl-10 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-[var(--blue1)]">
                            <option value="">Pilih Rhesus</option>
                            <option value="Positif">Positif</option>
                            <option value="Negatif">Negatif</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-plus-minus text-gray-400"></i>
                        </div>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Nomor BPJS Input Field -->
                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="BPJS_number">Nomor BPJS (Opsional)</label>
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Opsional"
                            name="BPJS_number" id="BPJS_number" value="">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-hospital text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between space-x-4 mt-6">
                    <button type="button" id="prev-step-btn" class="btn text-center w-1/2 py-3 rounded-lg text-gray-700 font-semibold shadow-lg
                        bg-gray-200 hover:bg-gray-300 transition-all duration-300 ease-in-out
                        focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-opacity-75 font-bold tracking-wide">
                        Kembali
                    </button>
                    <button id="registPatientForm" type="submit" class="btn text-center w-1/2 py-3 rounded-lg text-white font-semibold shadow-lg
                        bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)]
                        hover:from-purple-600 hover:to-indigo-700
                        hover:shadow-xl transition-all duration-300 ease-in-out
                        focus:outline-none focus:ring-2 focus:ring-[var(--blueGradient1)] focus:ring-opacity-75 font-bold tracking-wide">
                        Daftar Pasien
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('patientRegistrationForm');
        const step1Content = document.getElementById('step-1-content');
        const step2Content = document.getElementById('step-2-content');
        const nextStepBtn = document.getElementById('next-step-btn');
        const prevStepBtn = document.getElementById('prev-step-btn');
        const submitFormBtn = document.getElementById('registPatientForm');
        const errorContainer = document.getElementById('error-container');
        const errorList = document.getElementById('error-list');

        const stepperCircle1 = document.getElementById('circle-1');
        const stepperLabel1 = document.getElementById('label-1');
        const stepperCircle2 = document.getElementById('circle-2');
        const stepperLabel2 = document.getElementById('label-2');
        const stepperItem1 = document.getElementById('stepper-step-1');

        let currentStep = 1;

        // Function to update stepper UI
        function updateStepperUI() {
            if (currentStep === 1) {
                stepperCircle1.classList.add('active');
                stepperLabel1.classList.add('active');
                stepperCircle2.classList.remove('active', 'completed');
                stepperLabel2.classList.remove('active', 'completed');
                stepperItem1.classList.remove('completed');
            } else if (currentStep === 2) {
                stepperCircle1.classList.remove('active');
                stepperCircle1.classList.add('completed');
                stepperLabel1.classList.remove('active');
                stepperLabel1.classList.add('completed');
                stepperItem1.classList.add('completed');

                stepperCircle2.classList.add('active');
                stepperLabel2.classList.add('active');
            }
        }

        // Function to show a specific step
        function showStep(stepNumber) {
            step1Content.classList.add('hidden');
            step2Content.classList.add('hidden');

            if (stepNumber === 1) {
                step1Content.classList.remove('hidden');
            } else if (stepNumber === 2) {
                step2Content.classList.remove('hidden');
            }
            currentStep = stepNumber;
            updateStepperUI();
            // Clear and hide errors when switching steps or showing a step
            errorContainer.classList.add('hidden');
            errorList.innerHTML = '';
            // Remove all red borders from inputs
            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.classList.remove('border-red-500');
            });
        }

        // Helper function to check if all required fields in a step are filled
        // This is a basic client-side check for UX, but backend validation is authoritative.
        function checkRequiredFields(stepElement) {
            let allFieldsFilled = true;
            const requiredInputs = stepElement.querySelectorAll('[required]');
            requiredInputs.forEach(input => {
                input.classList.remove('border-red-500'); // Clear previous error styling
                if (input.type === 'date' && !input.value) {
                    allFieldsFilled = false;
                    input.classList.add('border-red-500');
                } else if (input.tagName === 'SELECT' && !input.value) {
                    allFieldsFilled = false;
                    input.classList.add('border-red-500');
                } else if (input.type !== 'date' && input.tagName !== 'SELECT' && input.value.trim() === '') {
                    allFieldsFilled = false;
                    input.classList.add('border-red-500');
                }
            });
            return allFieldsFilled;
        }

        // Handle "Next" button click
        nextStepBtn.addEventListener('click', function() {
            // Check if basic required fields in step 1 are filled for better UX
            if (!checkRequiredFields(step1Content)) {
                 Swal.fire({
                    icon: 'warning',
                    title: 'Lengkapi Data!',
                    text: 'Harap isi semua kolom yang wajib diisi di langkah ini.',
                    confirmButtonColor: '#3B82F6'
                });
                return; // Prevent moving to next step if required fields are empty
            }
            showStep(2);
        });

        // Handle "Previous" button click
        prevStepBtn.addEventListener('click', function() {
            showStep(1);
        });

        // Handle "Submit" button click
        submitFormBtn.addEventListener('click', async function(e) {
            e.preventDefault(); 

            if (!checkRequiredFields(step2Content)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lengkapi Data!',
                    text: 'Harap isi semua kolom yang wajib diisi di langkah ini.',
                    confirmButtonColor: '#3B82F6'
                });
                return; 
            }

            // Show confirmation dialog
            const result = await Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                text: "Apakah Anda yakin ingin mendaftarkan pasien ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Ya, Daftar!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                // User confirmed, now prepare and send the form data
                const formData = new FormData(form);
                const payload = {};
                for (const [key, value] of formData.entries()) {
                    payload[key] = value;
                }

                Swal.fire({
                    title: 'Memproses...',
                    text: 'Harap tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

                    const response = await fetch(form.action, {
                        method: form.method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json', // Explicitly ask for JSON response
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken 
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json(); // Get JSON response

                    if (response.ok) { // Check for successful HTTP status (2xx)
                        Swal.fire({
                            icon: 'success',
                            title: 'Pendaftaran Berhasil!',
                            text: data.message || 'Data pasien berhasil didaftarkan.',
                            confirmButtonColor: '#3B82F6'
                        }).then(() => {
                            form.reset(); // Clear form on success
                            showStep(1); // Go back to first step
                        });
                    } else { // Handle HTTP errors (e.g., 422 for validation, 500 for server error)
                        errorList.innerHTML = ''; // Clear previous errors
                        let errorMessageHtml = '<p>Pendaftaran gagal. Mohon periksa kembali input Anda:</p><ul class="mt-2 text-left">';
                        
                        // Clear all previous error highlights from inputs
                        document.querySelectorAll('input, select, textarea').forEach(el => {
                            el.classList.remove('border-red-500');
                        });

                        if (data.errors) { // Assuming Laravel sends validation errors in 'errors' object
                            for (const field in data.errors) {
                                const errorMessages = data.errors[field]; // This will be an array of messages
                                errorMessages.forEach(errorText => {
                                    const li = document.createElement('li');
                                    li.textContent = errorText;
                                    errorList.appendChild(li); // Add to on-page error list
                                    errorMessageHtml += `<li>${errorText}</li>`; // Add to Swal HTML
                                });
                                
                                // Highlight the input field if it exists
                                const inputField = document.getElementById(field);
                                if (inputField) {
                                    inputField.classList.add('border-red-500');
                                }
                            }
                        } else {
                            // General error message if no specific validation errors are provided
                            errorMessageHtml += `<li>${data.message || 'Terjadi kesalahan saat mendaftarkan pasien.'}</li>`;
                        }
                        errorMessageHtml += '</ul>';
                        errorContainer.classList.remove('hidden'); // Show on-page error container

                        Swal.fire({
                            icon: 'error',
                            title: 'Pendaftaran Gagal!',
                            html: errorMessageHtml, // Display all errors in Swal
                            confirmButtonColor: '#EF4444'
                        });

                        // If errors occurred in step 1 fields, show step 1
                        const step1Fields = ['nama_lengkap', 'nomor_telepon', 'jenis_kelamin', 'tanggal_lahir', 'nomor_ktp', 'profesi', 'alamat_tempat_tinggal'];
                        const hasStep1Errors = Object.keys(data.errors || {}).some(field => step1Fields.includes(field));
                        if (hasStep1Errors && currentStep !== 1) {
                            showStep(1); // Go back to step 1 if errors are found there
                        }
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Jaringan!',
                        text: 'Tidak dapat terhubung ke server. Mohon coba lagi.',
                        confirmButtonColor: '#EF4444'
                    });
                    console.error('Fetch error:', error);
                }
            }
        });

        // Initial display
        showStep(1); // Start on the first step

        // Check for old input and pre-populate if coming back from failed submission
        // This relies on Laravel's `old()` helper and a fresh page load after redirect with errors
        // If coming back from redirect, it will always be on step 1.
        // However, if we want to retain step for specific errors, we'd need more complex logic (e.g., passing step via session/flash)
        @if ($errors->any())
            // Show the error container immediately if there are Laravel backend errors
            errorContainer.classList.remove('hidden');
            errorList.innerHTML = '';
            @foreach ($errors->all() as $error)
                const li = document.createElement('li');
                li.textContent = "{{ $error }}";
                errorList.appendChild(li);
            @endforeach

            // Also trigger SweetAlert for overall error
            Swal.fire({
                icon: 'error',
                title: 'Pendaftaran Gagal!',
                html: 'Terdapat masalah dengan input Anda. Silakan periksa kembali formulir.',
                confirmButtonColor: '#EF4444'
            });

            // Highlight fields that have errors
            @foreach ($errors->keys() as $field)
                const errorField = document.getElementById('{{ $field }}');
                if (errorField) {
                    errorField.classList.add('border-red-500');
                }
            @endforeach

            // If any error belongs to step 2, navigate to step 2 after initial page load
            const step2Fields = ['golongan_darah', 'rhesus', 'nomor_bpjs'];
            const hasStep2Errors = step2Fields.some(field => document.getElementById(field) && document.getElementById(field).classList.contains('border-red-500'));
            if (hasStep2Errors) {
                showStep(2);
            }
        @endif
    });
</script> 
@endpush
                        