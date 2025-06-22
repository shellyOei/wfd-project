@push('head')
<style>
    /* #emergency-modal.close {
        bottom: -1000px!important;
    } */
</style>
    
@endpush

<div id="emergency-bg" class="close-emergency-modal h-screen w-screen fixed top-0 left-0 z-[998] bg-gray-700/50 transition-all duration-600 hidden">
<div id="emergency-modal" class="transition-all duration-500 absolute flex flex-col items-center  w-screen sm:w-fit h-fit px-12 py-8 bg-gray-300 z-[999] rounded-t-3xl sm:rounded-b-3xl drop-shadow-3xl bottom-[-1000px] sm:left-1/2 transform sm:-translate-x-1/2 sm:translate-y-1/2"> 
    <button class="close-emergency-modal bg-gray-600 hover:bg-gray-700 h-[13px] w-[140px] rounded-full drop-shadow-lg block sm:!hidden">

    </button>

    {{-- close button for desktop --}}
    <button class="close-emergency-modal drop-shadow-lg hidden sm:block ml-auto text-5xl font-bold">
        ×
    </button>

    <div class="mt-10 sm:mt-2 text-center mb-8">
        <p class="font-bold text-lg mb-1">GAWAT DARURAT</p>
        <p>Hubungi layanan darurat kami melalui pilihan di bawah</p>
    </div>

    

    <button onclick="window.location.href = '{{ route('user.emergency') }}'" class="w-full h-[150px] drop-shadow-xl rounded-xl bg-[var(--background)] px-4 py-8 flex justify-start items-center">
        <img class="h-full object-contain" src="{{ asset('assets/emergency/ambulance_light.png') }}" alt="emergency_light">
        <div class="text-start">
            <p class="font-bold">Pusat Bantuan Klinik Pratama</p>
            <p class="text-gray-600 text-xs">Dengan aplikasi</p>
        </div>
    </button>


    {{-- line --}}
    <div class="flex justify-center items-center w-full my-4">
        <div class="w-full h-[2px] bg-gray-700"></div>
        <p class="mx-2">atau</p>
        <div class="w-full h-[2px] bg-gray-700"></div>
    </div>


    <a href="tel:0318537925" class="w-full h-[150px] drop-shadow-xl rounded-xl bg-[var(--background)] px-4 py-8 flex justify-start items-center">
        <img class="h-[60%] mx-4 object-contain" src="{{ asset('assets/emergency/ambulance_phone.png') }}" alt="emergency_light">
        <div class="text-start">
            <p class="font-bold">Hotline Klinik Pratama</p>
            <p class="text-gray-600 text-xs">(031) 8537925</p>
        </div>
    </a>
</div>

</div>

@push('script')
    <script>
        const closeButtons = document.querySelectorAll('.close-emergency-modal');

        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                setTimeout(() => {
                    document.getElementById('emergency-modal').classList.add('bottom-[-1000px]');    
                    document.getElementById('emergency-modal').classList.remove('bottom-0', 'sm:bottom-1/2');
                    document.body.style.maxHeight = ''; 
                    document.body.style.overflow = ''; 
                }, 100);
                
                // document.getElementById('emergency-bg').style.opacity = 0;
                setTimeout(() => {
                    document.getElementById('emergency-bg').classList.add('hidden');
                }, 550);
            }) 
        });
    </script>
@endpush