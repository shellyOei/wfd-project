@extends('layout')

@section('style')
    <style>
        .bottom-nav-shadow {
            box-shadow: 0px 4px 15px 4px rgba(0, 0, 0, 0.25);
        }
    </style>
@endsection
@section('content')
    <div class="bg-[#f4f4fd] min-h-full flex justify-center items-center py-6 px-4">
        <div class="w-full max-w-md lg:max-w-2xl xl:max-w-4xl relative">
            <!-- Mobile Layout -->
            <div class="lg:hidden pb-4 p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <button onclick="goBackWithReload()"
                        class="text-gray-700 text-xl hover:text-blue-600 transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">Masukkan Pasien</h1>
                    <div class="w-6 h-6"></div>
                </div>

                <!-- Logo -->
                <div class="w-full flex items-center justify-center mb-8">
                    <img src="{{ asset('assets/ewaps-logo.png') }}" alt="Logo EWApps" class="w-20 h-auto">
                </div>

                <!-- Form -->
                <form id="patientForm" action="{{ route('user.link-patient.post') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Nama Pasien -->
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Nama Pasien</label>
                        <div class="relative">
                            <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap pasien"
                                class="w-full h-12 pl-10 pr-4 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition-all duration-200"
                                required />
                            <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Lahir</label>
                        <input type="date" id="date_of_birth" name="date_of_birth"
                            class="w-full h-12 px-4 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition-all duration-200"
                            required />
                    </div>

                    <!-- Nomor MRN -->
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Nomor MRN</label>
                        <div class="relative">
                            <input type="text" id="patient_number" name="patient_number" placeholder="Masukkan nomor MRN"
                                class="w-full h-12 pl-10 pr-4 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition-all duration-200"
                                required />
                            <i class="fas fa-id-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button id="submitFormBtn" type="submit"
                            class="w-full h-12 bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-600 text-white font-bold rounded-xl flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-user-plus"></i>
                            Masukkan Pasien
                        </button>
                    </div>
                </form>
            </div>

            <!-- Desktop Layout -->
            <div class="hidden lg:block bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="lg:flex lg:min-h-[600px]">
                    <!-- Left Side - Illustration/Info -->
                    <div
                        class="lg:w-1/2 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-8 lg:p-12 flex flex-col justify-center items-center text-white relative overflow-hidden">
                        <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                        <div class="relative z-10 text-center">
                            <div class="mb-8">
                                <img src="{{ asset('assets/ewaps-logo.png') }}" alt="Logo EWApps"
                                    class="w-24 h-auto mx-auto mb-6 filter brightness-0 invert">
                            </div>
                            <h2 class="text-3xl xl:text-4xl font-bold mb-4">Hubungkan Pasien</h2>
                            <p class="text-blue-100 text-lg mb-8 leading-relaxed">
                                Masukkan data pasien dengan lengkap dan akurat untuk memulai layanan kesehatan digital
                            </p>
                            <div class="space-y-4 text-left">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-sm"></i>
                                    </div>
                                    <span class="text-blue-100">Data aman dan terenkripsi</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-clock text-sm"></i>
                                    </div>
                                    <span class="text-blue-100">Proses cepat dan mudah</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-md text-sm"></i>
                                    </div>
                                    <span class="text-blue-100">Akses ke layanan medis</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Form -->
                    <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                        <!-- Header -->
                        <div class="flex flex-wrap">
                            <button onclick="goBackWithReload()"
                                class="mr-16 text-gray-600 hover:text-blue-600 transition-colors mb-4 flex items-center gap-2">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <h1 class="text-2xl xl:text-2xl font-bold text-gray-800 mb-2">Hubungkan Pasien</h1>
                        </div>
                        <p class="text-gray-600 mb-2 text-sm">Lengkapi formulir di bawah ini dengan data yang valid</p>

                        <!-- Form -->
                        <form id="patientFormDesktop" action="{{ route('user.link-patient.post') }}" method="POST"
                            class="space-y-6">
                            @csrf

                            <!-- Nama Pasien -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-3">Nama Lengkap Pasien</label>
                                <div class="relative">
                                    <input type="text" id="name_desktop" name="name"
                                        placeholder="Masukkan nama lengkap pasien"
                                        class="w-full h-14 pl-12 pr-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white transition-all duration-200 text-gray-800"
                                        required />
                                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-3">Tanggal Lahir</label>
                                <input type="date" id="date_of_birth_desktop" name="date_of_birth"
                                    class="w-full h-14 px-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white transition-all duration-200 text-gray-800"
                                    required />
                            </div>

                            <!-- Nomor MRN -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-3">Nomor MRN (Medical Record
                                    Number)</label>
                                <div class="relative">
                                    <input type="text" id="patient_number_desktop" name="patient_number"
                                        placeholder="Masukkan nomor MRN"
                                        class="w-full h-14 pl-12 pr-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 hover:bg-white transition-all duration-200 text-gray-800"
                                        required />
                                    <i class="fas fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            <div class="pt-6">
                                <button id="submitFormBtnDesktop" type="submit"
                                    class="w-full h-14 bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-600 text-white font-bold rounded-xl flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-lg">
                                    <i class="fas fa-user-plus"></i>
                                    Masukkan Pasien
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        function goBackWithReload() {
            const previous = document.referrer;
            if (previous) {
                window.location.href = previous; // Ini akan ke halaman sebelumnya dan melakukan reload penuh
            } else {
                window.history.back(); // fallback kalau referrer kosong (langsung akses halaman ini)
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('patientForm');
            const submitFormBtn = document.getElementById('submitFormBtn');

            // Fungsi validasi field wajib
            function checkRequiredFields() {
                let isValid = true;
                const requiredInputs = form.querySelectorAll('[required]');
                requiredInputs.forEach(input => {
                    input.classList.remove('border-red-500');
                    if (!input.value.trim()) {
                        input.classList.add('border-red-500');
                        isValid = false;
                    }
                });
                return isValid;
            }

            // Saat tombol submit ditekan
            submitFormBtn.addEventListener('click', async function (e) {
                e.preventDefault();

                if (!checkRequiredFields()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lengkapi Data!',
                        text: 'Harap isi semua kolom wajib diisi.',
                        confirmButtonColor: '#3B82F6'
                    });
                    return;
                }

                const confirmation = await Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin mendaftarkan pasien ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Daftar!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#3B82F6',
                    cancelButtonColor: '#EF4444'
                });

                if (!confirmation.isConfirmed) return;

                // Ambil data dari form
                const formData = new FormData(form);
                const payload = {};
                formData.forEach((value, key) => {
                    payload[key] = value;
                });

                // Tampilkan loading
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const response = await fetch(form.action, {
                        method: form.method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: result.message || 'Pasien berhasil dihubungkan.',
                            showConfirmButton: false,
                            timer: 1000
                            // confirmButtonColor: '#3B82F6'
                        });
                        form.reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: result.message || 'Terjadi kesalahan saat mendaftarkan pasien.',
                            confirmButtonColor: '#EF4444'
                        });
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Jaringan',
                        text: 'Tidak dapat terhubung ke server.',
                        confirmButtonColor: '#EF4444'
                    });
                }
            });
        });
    </script>
@endpush