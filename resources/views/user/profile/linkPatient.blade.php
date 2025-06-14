@extends('layout')

@section('style')
    <style>
        .bottom-nav-shadow {
            box-shadow: 0px 4px 15px 4px rgba(0, 0, 0, 0.25);
        }
    </style>
@endsection

@section('content')
    <div class="bg-[#f4f4fd] flex justify-center w-full min-h-screen py-6 px-4">
        <div class="w-full max-w-md relative bg-[#f4f4fd]">

            <!-- Header -->
            <div class="flex items-center justify-between my-4 mx-4">
                <button onclick="history.back()" class="text-[#292929] text-xl">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h1 class="text-xl font-bold text-[#292929]">Masukkan Pasien</h1>
                <div class="w-6 h-6"></div>
            </div>

            <!-- Logo -->
            <div class="w-full flex items-center justify-center my-4">
                <img src="{{ asset('assets/ewaps-logo.png') }}" alt="Logo EWApps" class="w-[80px] h-auto">
            </div>

            <!-- Form -->
            <form id="patientForm" action="{{ route('user.link-patient.post') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Nama Pasien -->
                <div>
                    <label class="block text-[#303030] text-sm mb-1">Nama Pasien</label>
                    <div class="relative">
                        <input type="text" id="name" name="name" placeholder="-"
                            class="w-full h-12 pl-10 pr-4 border border-[#497fff] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0e7afe] bg-white"
                            required />
                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-[#303030] text-sm mb-1">Tanggal Lahir</label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                        class="w-full h-12 px-4 border border-[#497fff] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0e7afe] bg-white"
                        required />
                </div>

                <!-- Nomor MRN -->
                <div>
                    <label class="block text-[#303030] text-sm mb-1">Nomor MRN</label>
                    <div class="relative">
                        <input type="text" id="patient_number" name="patient_number" placeholder="-"
                            class="w-full h-12 pl-10 pr-4 border border-[#497fff] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0e7afe] bg-white"
                            required />
                        <i class="fas fa-id-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="mt-8">
                    <button id="submitFormBtn" type="submit"
                        class="w-full h-12 bg-gradient-to-r from-[#4ADEDE] via-[#1CA7EC] to-[#1F2F98] text-white font-bold rounded-lg flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        Masukkan Pasien
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('script')
<script>
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
                        confirmButtonColor: '#3B82F6'
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
