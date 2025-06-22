<button onclick="quitEmergency()" class="fixed top-0 left-0 relative emergency-back-button">
    <div class="absolute w-[35px] rounded bg-gray-800 h-1.5 rotate-[-40deg] top-[30px] left-[13px]"></div>
    <div class="absolute w-[35px] rounded bg-gray-800 h-1.5 rotate-[40deg] top-[50px] left-[13px]"></div>
</button>

@push('script')
    <script>
        function quitEmergency () {
            Swal.fire({
                heightAuto: false,
                showDenyButton: true,
                denyButtonText: `Ya, keluar.`,
                cancelButtonText: `Tidak, batal.`,
                icon: 'question',
                title: 'Keluar?',
                text: 'Apakah anda ingin berhenti menggunakan layanan gawat darurat?',
                confirmButtonColor: "#3085d6",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.history.back()
                } 
            });
        }
    </script>
@endpush