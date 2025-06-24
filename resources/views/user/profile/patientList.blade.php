@extends('layout')

@section('style')
@endsection

@section('content')
<div class="min-h-screen bg-[#f4f4fd]">
    <!-- Mobile Layout -->
    <main class="max-w-[440px] md:hidden pt-8 mx-auto pb-[61px]">
        <section class="px-9">
            <!-- Header -->
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center">
                    <button onclick="goBackWithReload()" class="w-8 h-8 bg-[#f4f4fd] rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <h1 class="text-2xl font-bold">Daftar Pasien</h1>
                </div>
            </div>

            <!-- Pasien List -->
            <div class="space-y-4 mb-4">
                <h2 class="text-xl font-semibold">Pasien Terhubung</h2>

                @forelse ($patients as $patient)
                    @php
                        $initial = strtoupper(substr($patient->name, 0, 1));
                        $umur = \Carbon\Carbon::parse($patient->date_of_birth)->age;
                    @endphp

                    <div class="flex items-center bg-white rounded-2xl p-4 shadow hover:shadow-md transition">
                        <div class="w-[88px] h-[87px] rounded-full mr-4 flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-4xl">
                            {{ $initial }}
                        </div>

                        <div class="flex-grow">
                            <h3 class="text-xl font-bold">{{ $patient->name }}</h3>
                            <div class="flex flex-wrap items-center gap-2 mt-1 mb-2">
                                <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $patient->sex === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </div>

                            <div class="flex gap-3 mt-2">
                                <a href="{{ route('user.patients.edit.form', $patient->id) }}" class="text-sm font-semibold text-blue-600 hover:underline">Ubah</a>
                                <button onclick="disconnectPatient('{{ $patient->id }}', '{{ e($patient->name) }}', '{{ route('user.patients.disconnect', ['id' => $patient->id]) }}')" class="text-sm font-semibold text-red-600 hover:underline">
                                    Putuskan
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-[#a9a9a9] italic py-4 bg-white rounded-2xl p-4 shadow flex-shrink-0">
                        Belum ada pasien terhubung.
                    </p>
                @endforelse
            </div>
        </section>
    </main>

    <!-- Desktop Layout -->
    <div class="hidden md:block max-w-7xl mx-auto px-6 py-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl p-8 mb-8 shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button onclick="goBackWithReload()" class="w-12 h-12 bg-[#f4f4fd] hover:bg-gray-200 rounded-full flex items-center justify-center mr-6">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>
                    <h1 class="text-3xl font-bold text-gray-800">Daftar Pasien</h1>
                </div>

                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ count($patients) }}</div>
                        <div class="text-sm text-gray-600">Pasien Terhubung</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ 5 - count($patients) }}</div>
                        <div class="text-sm text-gray-600">Slot Tersedia</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-2xl p-8 shadow">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Pasien Terhubung</h2>
                <button id="patient-modal-btn" class="bg-gradient-to-r from-[#4ADEDE] via-[#1CA7EC] to-[#1F2F98] text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Tambah Pasien
                </button>
            </div>

            @if(count($patients) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($patients as $patient)
                        @php
                            $initial = strtoupper(substr($patient->name, 0, 1));
                            $umur = \Carbon\Carbon::parse($patient->date_of_birth)->age;
                        @endphp

                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 rounded-full flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-2xl mr-4">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $patient->name }}</h3>
                                        <div class="flex items-center gap-2">
                                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                                {{ $patient->sex === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                            <span class="bg-gray-100 text-gray-700 text-sm font-medium px-3 py-1 rounded-full">
                                                {{ $umur }} tahun
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl p-4 mb-4 border border-gray-100 text-sm grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-gray-500 font-medium">MRN:</span>
                                    <span class="text-gray-800 font-semibold ml-2">{{ $patient->patient_number ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 font-medium">Tanggal Lahir:</span>
                                    <span class="text-gray-800 font-semibold ml-2">
                                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('user.patients.edit.form', $patient->id) }}" class="flex-1 bg-blue-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                                    <i class="fas fa-edit"></i> Ubah
                                </a>
                                <button onclick="disconnectPatient('{{ $patient->id }}', '{{ e($patient->name) }}', '{{ route('user.patients.disconnect', ['id' => $patient->id]) }}')" class="flex-1 bg-red-600 text-white py-3 rounded-xl font-semibold hover:bg-red-700 transition flex items-center justify-center gap-2">
                                    <i class="fas fa-unlink"></i> Putuskan
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-gray-400 text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Pasien Terhubung</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">Mulai tambahkan pasien untuk mengelola informasi kesehatan mereka dengan lebih mudah.</p>
                    <button id="patient-modal-btn" class="bg-gradient-to-r from-[#4ADEDE] via-[#1CA7EC] to-[#1F2F98] text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Tambah Pasien Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
@push('scripts')
    <script>
        function goBackWithReload() {
            const fallback = document.referrer;
            const previous = sessionStorage.getItem('prevUrl') || fallback;

            if (previous && previous !== window.location.href) {
                window.location.href = previous;
            } else {
                window.history.back();
            }
        }
        function disconnectPatient(id, name, url) {
            Swal.fire({
                title: `Putuskan pasien ${name}?`,
                text: "Pasien akan dihapus dari daftar Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Putuskan'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Pasien telah diputus.',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1000
                                }).then(() => location.reload());
                            } else {
                                Swal.fire('Gagal', 'Gagal memutuskan pasien.', 'error');
                            }
                        });
                }
            });
        }

    </script>
@endpush