@extends('layout')

@section('style')
    <style>
        .bottom-nav-shadow {
            box-shadow: 0px 4px 15px 4px rgba(0, 0, 0, 0.25);
        }
    </style>
@endsection

@section('content')
    <div class="min-h-screen bg-[#f4f4fd]">

        <!-- Main Content -->
        <main class="max-w-[440px] mx-auto pt-[2rem] pb-20">
            <div class="flex items-center px-6 mb-8">
                <button onclick="goBackWithReload()"
                    class="w-10 h-10 bg-transparent rounded-full flex items-center justify-center mr-4 transition-shadow">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-800">Edit Akun</h1>
            </div>

            <!-- Profile Section -->
            <div class="px-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-center mb-4">
                        <div class="relative">
                            <img src="{{ asset('assets/profile-avatar.jpg')}}" alt="Profile"
                                class="w-24 h-24 rounded-full border-4 border-blue-100" />
                            <button
                                class="absolute -bottom-1 -right-1 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center shadow-lg hover:bg-blue-600 transition-colors">
                                <i class="fas fa-camera text-white text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-center text-gray-600 text-sm">Klik untuk mengubah foto profil</p>
                </div>
            </div>

            <!-- Form -->
            <form id="editAccountForm" class="px-6 pb-6 space-y-6">
                <!-- Name Field -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Nama Lengkap</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text"
                            class="w-full h-12 pl-11 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all duration-200 text-gray-800"
                            placeholder="Masukkan nama lengkap" value="{{ $user->name }}" />
                    </div>

                </div>

                <!-- Email Field -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Email Address</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="email"
                            class="w-full h-12 pl-11 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all duration-200 text-gray-800"
                            placeholder="contoh@gmail.com" value="{{ $user->email ?? '' }}" />
                    </div>
                </div>

                <!-- Phone Field
                                                        <div class="bg-white rounded-2xl p-6 shadow-sm">
                                                            <label class="block text-sm font-semibold text-gray-700 mb-3">Nomor Telepon</label>
                                                            <div class="relative">
                                                                <input type="tel" 
                                                                       class="w-full h-12 px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all duration-200 text-gray-800" 
                                                                       placeholder="+62 812 3456 7890" 
                                                                       value="{{ $user->phone ?? '' }}" />
                                                                <i class="fas fa-phone absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                                            </div>
                                                        </div> -->

                <!-- Password Reset Section -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1">Keamanan Akun</h3>
                            <p class="text-xs text-gray-500">Kelola password dan keamanan akun Anda</p>
                        </div>
                        <button type="button" onclick="showResetPasswordModal()"
                            class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors font-medium text-sm flex items-center gap-2">
                            <i class="fas fa-key text-xs"></i>
                            Reset Password
                        </button>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="pt-4">
                    <button id="submitBtn" type="submit"
                        class="w-full h-12 bg-gradient-to-r from-[#4ADEDE] via-[#1CA7EC] to-[#1F2F98] text-white font-bold rounded-lg flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
                <!-- Delete Account Button -->
                <div class="pt-2">
                    <div class="flex gap-4">
                        <!-- Nonaktifkan Akun -->
                        <button type="button" onclick="deactivateAccount()"
                            class="flex-1 h-12 bg-white border-2 border-yellow-300 text-yellow-700 font-semibold rounded-xl flex items-center justify-center gap-2 hover:bg-yellow-50 hover:border-yellow-400 transition-all duration-200">
                            <i class="fas fa-user-slash"></i>
                            <span>Nonaktifkan</span>
                        </button>

                        <!-- Hapus Akun -->
                        <button type="button" onclick="showDeleteAccountModal()"
                            class="flex-1 h-12 bg-white border-2 border-red-200 text-red-600 font-semibold rounded-xl flex items-center justify-center gap-2 hover:bg-red-50 hover:border-red-300 transition-all duration-200">
                            <i class="fas fa-trash-alt"></i>
                            <span>Hapus Akun</span>
                        </button>
                    </div>
                </div>

            </form>
        </main>
    </div>

    <!-- Reset Password Modal -->
    <div id="reset-password-modal"
        class="fixed inset-0 bg-black bg-opacity-0 opacity-0 pointer-events-none flex items-center justify-center px-4 transition-all duration-300 ease-in-out z-50">
        <div
            class="w-full max-w-sm bg-white rounded-2xl p-6 shadow-xl transform scale-95 transition-transform duration-300">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Reset Password</h3>
                <p class="text-gray-600 text-sm">Link reset password akan dikirim ke email Anda</p>
            </div>

            <div class="space-y-4">
                <button onclick="resetPassword()"
                    class="w-full h-12 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition-colors">
                    Kirim Link Reset
                </button>
                <button onclick="closeResetPasswordModal()"
                    class="w-full h-12 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="delete-account-modal"
        class="fixed inset-0 bg-black bg-opacity-0 opacity-0 pointer-events-none flex items-center justify-center px-4 transition-all duration-300 ease-in-out z-50">
        <div
            class="w-full max-w-sm bg-white rounded-2xl p-6 shadow-xl transform scale-95 transition-transform duration-300">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Akun</h3>
                <p class="text-gray-600 text-sm">Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus permanen.
                </p>
            </div>

            <div class="space-y-4">
                <button onclick="deleteAccount()"
                    class="w-full h-12 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-colors">
                    Ya, Hapus Akun
                </button>
                <button onclick="closeDeleteAccountModal()"
                    class="w-full h-12 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('editAccountForm');
            const submitBtn = form.querySelector('#submitBtn');

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Ambil data dari input
                const name = form.querySelector('input[type="text"]').value.trim();
                const email = form.querySelector('input[type="email"]').value.trim();

                // Validasi sederhana
                if (!name || !email) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data tidak lengkap',
                        text: 'Nama dan email harus diisi.',
                    });
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const response = await fetch("{{ route('user.update.post') }}", {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrf,
                            "Accept": "application/json",
                        },
                        body: JSON.stringify({ name, email })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message || 'Akun berhasil diperbarui.',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Terjadi kesalahan saat memperbarui akun.',
                        });
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal menghubungi server.',
                    });
                }

                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
            });
        });

        function showResetPasswordModal() {
            const modal = document.getElementById('reset-password-modal');
            const modalContent = modal.querySelector('.bg-white');

            modal.classList.remove('opacity-0', 'pointer-events-none', 'bg-opacity-0');
            modal.classList.add('opacity-100', 'pointer-events-auto', 'bg-opacity-50');

            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 50);
        }

        function closeResetPasswordModal() {
            const modal = document.getElementById('reset-password-modal');
            const modalContent = modal.querySelector('.bg-white');

            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.remove('opacity-100', 'pointer-events-auto', 'bg-opacity-50');
                modal.classList.add('opacity-0', 'pointer-events-none', 'bg-opacity-0');
            }, 150);
        }

        function resetPassword() {
            const isSuccess = Math.random() > 0.5; // Simulasi berhasil/gagal

            if (isSuccess) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Link reset password telah dikirim ke email Anda.',
                    confirmButtonColor: '#1CA7EC'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal mengirim link reset password. Coba lagi nanti.',
                    confirmButtonColor: '#d33'
                });
            }

            closeResetPasswordModal();
        }


        function showDeleteAccountModal() {
            const modal = document.getElementById('delete-account-modal');
            const modalContent = modal.querySelector('.bg-white');

            modal.classList.remove('opacity-0', 'pointer-events-none', 'bg-opacity-0');
            modal.classList.add('opacity-100', 'pointer-events-auto', 'bg-opacity-50');

            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 50);
        }

        function closeDeleteAccountModal() {
            const modal = document.getElementById('delete-account-modal');
            const modalContent = modal.querySelector('.bg-white');

            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.remove('opacity-100', 'pointer-events-auto', 'bg-opacity-50');
                modal.classList.add('opacity-0', 'pointer-events-none', 'bg-opacity-0');
            }, 150);
        }

        function goBackWithReload() {
            const previous = document.referrer;
            if (previous) {
                window.location.href = previous; // Ini akan ke halaman sebelumnya dan melakukan reload penuh
            } else {
                window.history.back(); // fallback kalau referrer kosong (langsung akses halaman ini)
            }
        }
        function deactivateAccount() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Menonaktifkan...',
                text: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch("{{ route('user.deactivate') }}", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                    "Accept": "application/json",
                }
            })
                .then(async res => {
                    const data = await res.json();

                    if (res.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Akun Dinonaktifkan',
                            // text: data.message || 'Akun Anda telah dinonaktifkan.',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        setTimeout(() => {
                            window.location.href = "{{ route('login') }}";
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Gagal menonaktifkan akun.');
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: err.message,
                    });
                });
        }


        async function deleteAccount() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Menghapus akun...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch("{{ route('user.delete') }}", {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                        "Accept": "application/json"
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Akun Berhasil Dihapus',
                        // text: result.message || "Akun Anda telah berhasil dihapus.",
                        showConfirmButton: false,
                        timer: 2000
                    });

                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 2000);
                } else {
                    throw new Error(result.message || "Gagal menghapus akun.");
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message
                });
            } finally {
                closeDeleteAccountModal();
            }
        }



        document.getElementById('reset-password-modal').addEventListener('click', (e) => {
            if (e.target.id === 'reset-password-modal') {
                closeResetPasswordModal();
            }
        });

        document.getElementById('delete-account-modal').addEventListener('click', (e) => {
            if (e.target.id === 'delete-account-modal') {
                closeDeleteAccountModal();
            }
        });


    </script>
@endsection