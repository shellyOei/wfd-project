<div id="patient-modal" class="fixed inset-0 z-[99] flex items-end md:items-center justify-center pointer-events-none">
    <div id="patient-backdrop" class="absolute inset-0 bg-black opacity-0 transition-opacity duration-300 ease-in-out">
    </div>

    <div id="patient-content"
        class="relative w-full max-w-md bg-gray-200 rounded-t-[32px] md:rounded-2xl py-10 px-5 md:py-8 md:px-6 shadow-lg transform transition-all duration-300 ease-in-out 
               translate-y-full md:translate-y-0 md:scale-95 md:opacity-0">
        
        <div class="w-40 h-2 bg-neutral-600 rounded-full mx-auto mb-6 md:hidden"></div>

        <p class="text-center text-lg font-bold text-black leading-snug mb-8">
            Apakah Anda memiliki nomor rekam medis <span class="bg-yellow-300 rounded px-1.5">MRN</span> di Rumah Sakit Endang Widayat?
        </p>

        <a href="{{ route('user.link-patient') }}"
            class="block bg-white rounded-2xl shadow-md px-4 py-5 mb-5 cursor-pointer hover:bg-gray-50 transition-colors duration-200">
            <p class="text-center text-black font-semibold text-base">Ya, saya pasien lama</p>
            <p class="text-center text-gray-500 text-sm mt-1">
                Saya sudah terdaftar dan memiliki nomor rekam medis (MRN).
            </p>
        </a>

        <a href="{{ route('user.register.patient') }}"
            class="block bg-white rounded-2xl shadow-md px-4 py-5 cursor-pointer hover:bg-gray-50 transition-colors duration-200">
            <p class="text-center text-black font-semibold text-base">Tidak, saya pasien baru</p>
            <p class="text-center text-gray-500 text-sm mt-1">
                Saya belum pernah terdaftar dan belum memiliki nomor rekam medis.
            </p>
        </a>
    </div>
</div>

@push('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Ensure you have a button with id="patient-modal-btn" somewhere on your page to trigger this modal.
    const modalBtn = document.getElementById("patient-modal-btn");
    const modal = document.getElementById("patient-modal");
    
    // Check if the trigger button exists to avoid errors
    if (!modalBtn) {
        console.warn('Modal trigger button with id="patient-modal-btn" not found.');
        return;
    }

    const backdrop = document.getElementById("patient-backdrop");
    const content = document.getElementById("patient-content");

    const openModal = () => {
        modal.classList.remove("pointer-events-none");

        // Show backdrop
        backdrop.classList.remove("opacity-0");
        backdrop.classList.add("opacity-50");

        // Animate content in
        // This handles both mobile (slide-up) and desktop (scale-up/fade-in)
        content.classList.remove("translate-y-full", "md:scale-95", "md:opacity-0");
    };

    const closeModal = () => {
        // Hide backdrop
        backdrop.classList.add("opacity-0");
        backdrop.classList.remove("opacity-50");

        // Animate content out
        // This handles both mobile (slide-down) and desktop (scale-down/fade-out)
        content.classList.add("translate-y-full", "md:scale-95", "md:opacity-0");

        // Disable pointer events after the animation finishes
        setTimeout(() => {
            modal.classList.add("pointer-events-none");
        }, 300); // Should match the transition duration
    };

    modalBtn.addEventListener("click", openModal);
    backdrop.addEventListener("click", closeModal);
});
</script>
@endpush