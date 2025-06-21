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
            padding-left: 1rem;
            /* Adjust padding for textarea if no icon is present */
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
            background-color: #D1D5DB;
            /* light gray */
            left: 50%;
            top: 15px;
            /* Adjust to align with circle center */
            transform: translateX(0%);
            z-index: -1;
        }

        .stepper-item.completed::after {
            background-color: var(--blue1);
            /* Blue when completed */
        }

        .stepper-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #D1D5DB;
            /* light gray */
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
            font-size: 0.875rem;
            /* text-sm */
            color: #6B7280;
            /* gray-500 */
        }

        .stepper-label.active {
            color: var(--blue1);
        }

        .stepper-label.completed {
            color: var(--blue1);
        }

        /* New style for error messages below inputs */
        .error-message {
            color: #EF4444;
            /* red-500 */
            font-size: 0.75rem;
            /* text-xs */
            margin-top: 0.25rem;
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
            <div class="w-1/4 h-[2px] bg-[#D1D5DB] stepper-line"></div>
            <div class="stepper-item" id="stepper-step-2">
                <div class="stepper-circle" id="circle-2">2</div>
                <div class="stepper-label" id="label-2">Detail Medis</div>
            </div>
        </div>

        @php
            $isEdit = isset($patient);
            $formAction = $isEdit ? route('user.patients.update', $patient->id) : route('user.register.patient.post');
            $formMethod = $isEdit ? 'PUT' : 'POST';
        @endphp
        <form class="w-[85%] max-w-md space-y-4" action="{{ $formAction }}" method="POST" id="patientRegistrationForm">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div id="error-container"
                class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">Terdapat masalah dengan input Anda.</span>
                <ul id="error-list" class="mt-3 list-disc list-inside">
                </ul>
            </div>

            <!-- Step 1-->
            <div id="step-1-content" class="form-section">
                <h2 class="text-xl font-bold mb-4 text-center">
                    {{ $isEdit ? 'Ubah Data Pasien' : 'Langkah 1: Informasi Pribadi' }}
                </h2>


                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold wetext-md" for="name">Nama Lengkap</label>
                    <div class="relative">
                         <input type="text"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent;"
                            placeholder="Contoh: Budi Santoso" name="name" id="name"
                            value="{{ old('name', $patient->name ?? '') }}" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-400"></i>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="name-error"></span>
                </div>

                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="phone">Nomor Telepon</label>
                    <div class="relative">
                      <input type="tel"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent;"
                            placeholder="Contoh: 081234567890" name="phone" id="phone"
                            value="{{ old('phone', $patient->phone ?? '') }}" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-phone text-gray-400"></i>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="phone-error"></span>
                </div>

                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="sex">Jenis Kelamin</label>
                    <div class="relative">
                        <select name="sex" id="sex" required
                            class="block appearance-none w-full bg-white border border-2 border-[var(--blue1)] text-gray-700 py-3 pl-10 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-[var(--blue1)]">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="male" {{ (old('sex', $patient->sex ?? '') == 'male') ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="female" {{ (old('sex', $patient->sex ?? '') == 'female') ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-venus-mars text-gray-400"></i>
                        </div>
                        {{-- <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div> --}}
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="sex-error"></span>
                </div>

                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="date_of_birth">Tanggal Lahir</label>
                    <div class="relative">
                        <input type="date"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent;"
                            name="date_of_birth" id="date_of_birth"
                            value="{{ old('date_of_birth', $patient->date_of_birth ?? '') }}" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-calendar-days text-gray-400"></i>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="date_of_birth-error"></span>
                </div>

                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="id_card_number">Nomor KTP</label>
                    <div class="relative">
                         <input type="text"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent;"
                            placeholder="Contoh: 1234567890123456" name="id_card_number" id="id_card_number"
                            value="{{ old('id_card_number', $patient->id_card_number ?? '') }}" pattern="[0-9]{16}"
                            title="Nomor KTP harus 16 digit angka" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-id-card text-gray-400"></i>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="id_card_number-error"></span>
                </div>

                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="emergency_contact">Kontak Darurat (Opsional)</label>
                    <div class="relative">
                         <input type="text"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent;"
                            placeholder="Contoh: 1234567890123456" name="emergency_contact" id="emergency_contact"
                            value="{{ old('emergency_contact', $patient->emergency_contact ?? '') }}" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-address-book text-gray-400"></i>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="emergency_contact-error"></span>
                </div>

                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="occupation">Profesi</label>
                    <div class="relative">
                        <input type="text"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent;"
                            placeholder="Contoh: Karyawan Swasta" name="occupation" id="occupation"
                            value="{{ old('occupation', $patient->occupation ?? '') }}" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-briefcase text-gray-400"></i>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="occupation-error"></span>
                </div>

                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="address">Alamat Tempat Tinggal</label>
                    <div class="relative">
                        <textarea placeholder="Contoh: Jl. Merdeka No. 10, Jakarta" name="address" id="address" rows="3"
                            required
                            class="w-full pl-4 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent">{{ old('address', $patient->address ?? '') }}</textarea>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="address-error"></span>
                </div>

                <button type="button" id="next-step-btn"
                    class="btn text-center w-full py-3 rounded-lg text-white font-semibold shadow-lg
                                                                                    bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)]
                                                                                    hover:from-purple-600 hover:to-indigo-700
                                                                                    hover:shadow-xl transition-all duration-300 ease-in-out
                                                                                    focus:outline-none focus:ring-2 focus:ring-[var(--blueGradient1)] focus:ring-opacity-75 font-bold tracking-wide">
                    Selanjutnya
                </button>
            </div>

            <div id="step-2-content" class="form-section hidden space-y-4">
                <h2 class="text-xl font-bold mb-4 text-center">Langkah 2: Detail Medis & Lainnya</h2>

                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="blood_type">Golongan Darah</label>
                    <div class="relative">
                        <select name="blood_type" id="blood_type" required
                            class="block appearance-none w-full bg-white border border-2 border-[var(--blue1)] text-gray-700 py-3 pl-10 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-[var(--blue1)]">
                            <option value="">Pilih Golongan Darah</option>
                             @foreach(['A', 'B', 'AB', 'O'] as $bt)
                                <option value="{{ $bt }}" {{ (old('blood_type', $patient->blood_type ?? '') == $bt) ? 'selected' : '' }}>{{ $bt }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-droplet text-gray-400"></i>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="blood_type-error"></span>
                </div>

                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="rhesus_factor">Rhesus</label>
                    <div class="relative">
                        <select name="rhesus_factor" id="rhesus_factor" required
                            class="block appearance-none w-full bg-white border border-2 border-[var(--blue1)] text-gray-700 py-3 pl-10 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-[var(--blue1)]">
                            <option value="">Pilih Rhesus</option>
                            <option value="Positif" {{ (old('rhesus_factor', $patient->rhesus_factor ?? '') == '+') ? 'selected' : '' }}>Positif</option>
                            <option value="Negatif" {{ (old('rhesus_factor', $patient->rhesus_factor ?? '') == '-') ? 'selected' : '' }}>Negatif</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-plus-minus text-gray-400"></i>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="rhesus_factor-error"></span>
                </div>

                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="allergy">Alergi</label>
                    <div class="relative">
                        <input type="text"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent;"
                            placeholder="Contoh: kacang, apel" name="allergy" id="allergy"
                            value="{{ old('allergy', $patient->allergy ?? '') }}" required>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-triangle-exclamation text-gray-400"></i>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="allergy-error"></span>
                </div>


                <div class="form-group w-full flex flex-col space-y-1">
                    <label class="font-semibold text-md" for="BPJS_number">Nomor BPJS (Opsional)</label>
                    <div class="relative">
                        <input type="text"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-2 border-[var(--blue1)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-transparent;"
                            placeholder="Opsional" name="BPJS_number" id="BPJS_number"
                            value="{{ old('BPJS_number', $patient->BPJS_number ?? '') }}">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-hospital text-gray-400"></i>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1" id="BPJS_number-error"></span>
                </div>

                <div class="flex justify-between space-x-4 mt-6">
                    <button type="button" id="prev-step-btn"
                        class="btn text-center w-1/2 py-3 rounded-lg text-gray-700 font-semibold shadow-lg
                                                                                        bg-gray-200 hover:bg-gray-300 transition-all duration-300 ease-in-out
                                                                                        focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-opacity-75 font-bold tracking-wide">
                        Kembali
                    </button>
                    <button id="registPatientForm" type="submit"
                        class="btn text-center w-1/2 py-3 rounded-lg text-white font-semibold shadow-lg
                                                                    bg-gradient-to-r from-[var(--blueGradient1)] to-[var(--blueGradient2)]
                                                                    hover:from-purple-600 hover:to-indigo-700
                                                                    hover:shadow-xl transition-all duration-300 ease-in-out
                                                                    focus:outline-none focus:ring-2 focus:ring-[var(--blueGradient1)] focus:ring-opacity-75 font-bold tracking-wide">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Daftar Pasien' }}
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection

@push('script')
    <script>
        const isEditMode = @json(isset($patient));

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('patientRegistrationForm');
            const step1Content = document.getElementById('step-1-content');
            const step2Content = document.getElementById('step-2-content');
            const nextStepBtn = document.getElementById('next-step-btn');
            const prevStepBtn = document.getElementById('prev-step-btn');
            const submitFormBtn = document.getElementById('registPatientForm');
            const errorContainer = document.getElementById('error-container');
            const errorList = document.getElementById('error-list');
            const stepperLine = document.querySelector('.stepper-line');

            const stepperCircle1 = document.getElementById('circle-1');
            const stepperLabel1 = document.getElementById('label-1');
            const stepperCircle2 = document.getElementById('circle-2');
            const stepperLabel2 = document.getElementById('label-2');
            const stepperItem1 = document.getElementById('stepper-step-1');

            let currentStep = 1;

            function clearErrorState() {
                document.querySelectorAll('input, select, textarea').forEach(el => {
                    el.classList.remove('border-red-500');
                });
                document.querySelectorAll('.error-message').forEach(el => {
                    el.textContent = '';
                });
            }

            function updateStepperUI() {
                if (currentStep === 1) {
                    stepperCircle1.classList.add('active');
                    stepperLabel1.classList.add('active');
                    stepperCircle2.classList.remove('active', 'completed');
                    stepperLabel2.classList.remove('active', 'completed');
                    stepperItem1.classList.remove('completed');
                    stepperLine.classList.add('bg-[#D1D5DB]');
                    stepperLine.classList.remove('bg-[var(--blue1)]');
                } else if (currentStep === 2) {
                    stepperCircle1.classList.remove('active');
                    stepperCircle1.classList.add('completed');
                    stepperLabel1.classList.remove('active');
                    stepperLabel1.classList.add('completed');
                    stepperItem1.classList.add('completed');

                    stepperCircle2.classList.add('active');
                    stepperLabel2.classList.add('active');

                    stepperLine.classList.remove('bg-[#D1D5DB]');
                    stepperLine.classList.add('bg-[var(--blue1)]');
                }
            }

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
                // clearErrorState();
            }

            // function checkRequiredFields(stepElement) {
            //     let allFieldsFilled = true;
            //     const requiredInputs = stepElement.querySelectorAll('[required]');
            //     requiredInputs.forEach(input => {
            //         input.classList.remove('border-red-500');
            //         if (input.type === 'date' && !input.value) {
            //             allFieldsFilled = false;
            //             input.classList.add('border-red-500');
            //         } else if (input.tagName === 'SELECT' && !input.value) {
            //             allFieldsFilled = false;
            //             input.classList.add('border-red-500');
            //         } else if (input.type !== 'date' && input.tagName !== 'SELECT' && input.value.trim() === '') {
            //             allFieldsFilled = false;
            //             input.classList.add('border-red-500');
            //         }
            //     });
            //     return allFieldsFilled;
            // }

            nextStepBtn.addEventListener('click', function () {
                // if (!checkRequiredFields(step1Content)) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Lengkapi Data!',
                //         text: 'Harap isi semua kolom yang wajib diisi di langkah ini.',
                //         confirmButtonColor: '#3B82F6'
                //     });
                //     return;
                // }
                showStep(2);
            });

            prevStepBtn.addEventListener('click', function () {
                showStep(1);
            });

            submitFormBtn.addEventListener('click', async function (e) {
                e.preventDefault();

                // if (!checkRequiredFields(step2Content)) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: isEditMode ? 'Lengkapi Data Perubahan!' : 'Lengkapi Data!',
                //         text: 'Harap isi semua kolom yang wajib diisi di langkah ini.',
                //         confirmButtonColor: '#3B82F6'
                //     });
                //     return;
                // }

                const result = await Swal.fire({
                    title: isEditMode ? 'Konfirmasi Perubahan Data' : 'Konfirmasi Pendaftaran',
                    text: isEditMode
                        ? "Apakah Anda yakin ingin menyimpan perubahan data pasien ini?"
                        : "Apakah Anda yakin ingin mendaftarkan pasien ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3B82F6',
                    cancelButtonColor: '#EF4444',
                    confirmButtonText: isEditMode ? 'Ya, Simpan!' : 'Ya, Daftar!',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
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
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await response.json();

                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: isEditMode ? 'Perubahan Disimpan!' : 'Pendaftaran Berhasil!',
                                text: data.message || (isEditMode
                                    ? 'Data pasien berhasil diperbarui.'
                                    : 'Data pasien berhasil didaftarkan.'),
                                showConfirmButton: false,
                                timer: 1000
                            }).then(() => {
                                if (isEditMode) {
                                    window.location.href = '{{ route('user.patients') }}';
                                } else {
                                    // form.reset();
                                    // showStep(1);
                                    window.history.back();
                                }
                            });
                        } else {
                            clearErrorState();

                            if (data.errors) {
                                let hasStep1Errors = false;
                                const step1Fields = ['name', 'phone', 'sex', 'date_of_birth', 'id_card_number', 'occupation', 'address'];

                                for (const field in data.errors) {
                                    const inputField = document.getElementById(field);  
                                    if (inputField) {
                                        const errorSpan = document.getElementById(`${field}-error`);
                                        inputField.classList.add('border-red-500');
                                        console.log('Setting error state for:', errorSpan);
                                        if (errorSpan) {
                                            console.log('Setting error text for:');
                                            errorSpan.textContent = data.errors[field][0];
                                        }
                                        if (step1Fields.includes(field)) {
                                            hasStep1Errors = true;
                                        }
                                    }
                                }

                                // Show the general error container and list all errors
                                // errorContainer.classList.remove('hidden');
                                // errorList.innerHTML = '';
                                
                                // for (const field in data.errors) {
                                //     const li = document.createElement('li');
                                //     li.textContent = data.errors[field][0];
                                //     errorList.appendChild(li);
                                // }

                                if (hasStep1Errors) { 
                                    showStep(1); 
                                } else { 
                                    showStep(2);
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pendaftaran Gagal!',
                                    text: 'Mohon periksa kembali input Anda.',
                                    confirmButtonColor: '#EF4444'
                                });
                            } else {
                                //general error message
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pendaftaran Gagal!',
                                    text: data.message || 'Terjadi kesalahan saat mendaftarkan pasien.',
                                    confirmButtonColor: '#EF4444'
                                });
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

            showStep(1); 

            @if ($errors->any())
                clearErrorState(); 

                const laravelErrors = @json($errors->messages());
                let hasStep1LaravelErrors = false;
                const step1Fields = ['name', 'phone', 'sex', 'date_of_birth', 'id_card_number', 'occupation', 'address'];

                for (const field in laravelErrors) {
                    const inputField = document.getElementById(field);
                    if (inputField) {
                        displayFieldError(inputField, laravelErrors[field][0]);
                        if (step1Fields.includes(field)) {
                            hasStep1LaravelErrors = true;
                        }
                    }
                }

                @foreach ($errors->keys() as $field)
                    const errorField = document.getElementById('{{ $field }}');
                    const errorSpan = document.getElementById('{{ $field }}-error');
                    if (errorField) {
                        errorField.classList.add('border-red-500');
                    }
                    if (errorSpan) {
                        errorSpan.textContent = "{{ $errors->first($field) }}";
                    }
                @endforeach

                if (!hasStep1LaravelErrors && Object.keys(laravelErrors).length > 0) {
                    showStep(2); // If errors are only in step 2, show step 2
                } else {
                    showStep(1); // Otherwise, show step 1 (default or if step 1 errors exist)
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Pendaftaran Gagal!',
                    text: 'Terdapat beberapa kesalahan dalam pengisian formulir Anda.',
                    confirmButtonColor: '#EF4444'
                });
            @endif
        });
    </script>
@endpush