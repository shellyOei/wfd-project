<div id="patient-modal" class="fixed inset-0 z-[99] flex items-end justify-center pointer-events-none">
  <!-- BACKDROP  -->
  <div id="patient-backdrop" class="absolute inset-0 bg-black opacity-0 transition-opacity duration-300 ease-in-out">
  </div>

  <div id="patient-content"
    class="relative w-full max-w-md bg-gray-200 rounded-t-[32px] py-10 px-5 shadow-md transform translate-y-full transition-transform duration-300 ease-in-out">
    <!-- Garis hitam atas -->
    <div class="w-40 h-3 bg-neutral-700 rounded-full mx-auto mb-6"></div>

    <!-- Pertanyaan -->
    <p class="text-center text-base font-bold text-black leading-snug mb-8">
      Apakah Anda memiliki nomor<br />
      rekam medis <span class="bg-yellow-300 rounded px-1">MRN</span> di Rumah<br />
      Sakit Endang Widayat?
    </p>

    <!-- Box Pasien Lama -->
    <a href="{{ route('user.link-patient') }}"
      class="block bg-white rounded-2xl shadow-md px-4 py-5 mb-5 cursor-pointer">
      <p class="text-center text-black font-semibold text-sm">Ya, saya pasien lama</p>
      <p class="text-center text-gray-500 text-xs mt-1">
        Saya sudah terdaftar dan sudah<br />
        memiliki nomor rekam medis MRN sebelumnya
      </p>
    </a>

    <!-- Box Pasien Baru -->
    <a href="{{ route('user.register.patient') }}"
      class="block bg-white rounded-2xl shadow-md px-4 py-5 cursor-pointer">
      <p class="text-center text-black font-semibold text-sm">Tidak, saya pasien baru</p>
      <p class="text-center text-gray-500 text-xs mt-1">
        Saya belum terdaftar dan belum<br />
        memiliki nomor rekam medis
      </p>
    </a>


  </div>
</div>

@push('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    
  const modalBtn = document.getElementById("patient-modal-btn");
  const modal = document.getElementById("patient-modal");

  console.log(modalBtn, modal);
  const backdrop = document.getElementById("patient-backdrop");
  const content = document.getElementById("patient-content");

  modalBtn.addEventListener("click", () => {
    modal.classList.remove("pointer-events-none");

    // show backdrop
    backdrop.classList.remove("opacity-0");
    backdrop.classList.add("opacity-50");

    // slide-up modal
    content.classList.remove("translate-y-full");
    content.classList.add("translate-y-0");
  });

  backdrop.addEventListener("click", () => {
    // hide backdrop
    backdrop.classList.add("opacity-0");
    backdrop.classList.remove("opacity-50");

    // slide-down modal
    content.classList.add("translate-y-full");
    content.classList.remove("translate-y-0");

    // disable pointer event ketika sudah animasi selesai
    setTimeout(() => {
      modal.classList.add("pointer-events-none");
    }, 300);
  });
});   

  
</script>
@endpush

