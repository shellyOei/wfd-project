@extends('layout')

@section('style')
@endsection

@section('content')
    <div class="min-h-screen bg-[#f4f4fd]">
        <main class="max-w-[440px] md:max-w-7xl pt-[2rem] mx-auto md:pt-8 pb-[61px] md:pb-8">
            <section class="px-9 md:px-0">
                <!-- Header -->
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center">
                        <button onclick="goBackWithReload()"
                            class="w-8 h-8 bg-[#f4f4fd] rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                        <h1 class="text-2xl md:text-3xl font-bold">Daftar Pasien</h1>
                    </div>
                </div>

                <!-- Pasien List -->
                <div class="space-y-4 mb-4 md:grid md:grid-cols-1 md:gap-4 md:space-y-0">
                    <h2 class="text-xl md:text-3xl font-semibold">Pasien Terhubung</h2>

                    @forelse ($patients as $patient)
                        <div class="flex items-center bg-white rounded-2xl p-4 md:p-6 transition-shadow">
                            @php
                                $initial = strtoupper(substr($patient->name, 0, 1));
                                $umur = \Carbon\Carbon::parse($patient->date_of_birth)->age;
                            @endphp

                            <div
                                class="w-[88px] h-[87px] md:w-20 md:h-20 rounded-full mr-4 md:mr-6 flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-4xl">
                                {{ $initial }}
                            </div>

                            <div class="flex-grow">
                                <h3 class="text-xl md:text-xl font-bold">{{ $patient->name }}</h3>
                                <div class="flex flex-wrap items-center gap-2 mt-1 mb-2">
                                    <span
                                        class="bg-gray-100 text-gray-600 text-xs font-medium md:px-4 md:py-1 px-2 py-1 rounded-full">
                                        {{ $patient->sex === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                    <!-- <span
                                                                class="bg-gray-100 text-gray-600 text-xs font-medium md:px-4 md:py-1 px-2 py-1 rounded-full">
                                                                {{ $umur }} tahun
                                                            </span> -->
                                </div>

                                <div class="flex gap-3 mt-2">
                                    <a href="{{ route('user.patients.edit.form', $patient->id) }}"
                                        class="text-sm font-semibold text-blue-600 hover:underline">Ubah</a>

                                    <button
                                        onclick="disconnectPatient('{{ $patient->id }}', '{{ e($patient->name) }}', '{{ route('user.patients.disconnect', ['id' => $patient->id]) }}')"
                                        class="text-sm font-semibold text-red-600 hover:underline">
                                        Putuskan
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p
                            class="text-center text-[#a9a9a9] italic py-4 min-w-[260px] bg-white rounded-2xl p-4 shadow-sm flex-shrink-0">
                            Belum ada pasien terhubung.</p>
                    @endforelse
                </div>
            </section>
        </main>

        @include('partials.user-nav')
    </div>
@endsection

@push('scripts')
    <script>
        function goBackWithReload() {
            const previous = document.referrer;
            if (previous) {
                window.location.href = previous; // Ini akan ke halaman sebelumnya dan melakukan reload penuh
            } else {
                window.history.back(); // fallback kalau referrer kosong (langsung akses halaman ini)
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