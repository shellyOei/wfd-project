@push('head')
    <style>
        /* Optional: Custom utility for iOS safe area if needed and not handled by Tailwind variants */
        /* @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .pb-safe {
                padding-bottom: env(safe-area-inset-bottom);
            }
        } */
    </style>
@endpush

    <div class="text-center max-w-xl bg-white p-8 rounded-lg shadow-lg pb-3">
        <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition-colors duration-200">
            Open Bottom Sheet
        </button>
    </div>

    <div id="bottomSheetOverlay" class="fixed inset-0 bg-black/50 flex justify-center items-end z-[1000] invisible opacity-0 transition-opacity duration-300">
        <div id="bottomSheetContent" class="bg-[var(--gray2)] w-full max-w-md rounded-t-[4rem] shadow-2xl transform translate-y-full transition-transform duration-300 ease-out-expo p-2">
            <div class="flex w-full justify-between items-center p-4">
                <div class="bg-gray-600 w-[35%] h-[10px] mx-auto rounded-full"></div>
                {{-- <h2 class="text-xl font-semibold text-gray-800">Modal Title</h2> --}}
            </div>
            <div class="p-3 mx-auto w-[80%] text-black max-h-[70vh] overflow-y-auto flex flex-col items-center space-y-5">
                <p class="w-full text-center text-lg text-black font-bold">Apakah Anda memiliki nomor rekam medis (MRN) di Rumah Sakit Endang Widayat?</p>
               
                <div tabindex="0"
                     class="js-selection-option w-full bg-white space-y-2 shadow-md text-center p-4 rounded-3xl p-5
                            cursor-pointer transition-all duration-200 ease-in-out
                            hover:shadow-lg hover:scale-[1.01]
                            focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-[var(--blue1)]"
                     data-route="{{ route('user.dashboard') }}"> 
                    <p class="text-black font-semibold">Ya, saya pasien lama</p>
                    <p class="text-[var(--gray-inactive)] text-sm">Saya sudah terdaftar dan sudah memiliki nomor rekam medis (MRN) sebelumnya.</p>
                </div>

                <div tabindex="0"
                     class="js-selection-option w-full bg-white space-y-2 shadow-md text-center p-4 rounded-3xl p-5
                            cursor-pointer transition-all duration-200 ease-in-out
                            hover:shadow-lg hover:scale-[1.01]
                            focus:outline-none focus:ring-2 focus:ring-[var(--blue1)] focus:border-[var(--blue1)]"
                     data-route="{{ route('user.register.patient') }}"> 
                    <p class="text-black font-semibold">Tidak, saya pasien baru</p>
                    <p class="text-[var(--gray-inactive)] text-sm">Saya belum terdaftar dan belum memiliki nomor rekam medis.</p>
                </div>
            </div>
        </div>
    </div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openModalBtn = document.getElementById('openModalBtn');
            const bottomSheetOverlay = document.getElementById('bottomSheetOverlay');
            const bottomSheetContent = document.getElementById('bottomSheetContent');
            const selectionOptions = document.querySelectorAll('.js-selection-option');

            function openBottomSheet() {
                console.log('openBottomSheet function called'); // Log when the function starts
                bottomSheetOverlay.classList.remove('invisible', 'opacity-0');
                bottomSheetOverlay.classList.add('visible', 'opacity-100');
                bottomSheetContent.classList.remove('translate-y-full');
                bottomSheetContent.classList.add('translate-y-0');
                document.body.style.overflow = 'hidden';
            }

            function closeBottomSheet() {
                console.log('closeBottomSheet function called'); // Log for closing
                bottomSheetContent.classList.remove('translate-y-0');
                bottomSheetContent.classList.add('translate-y-full');
                bottomSheetContent.addEventListener('transitionend', function handler() {
                    bottomSheetOverlay.classList.remove('visible', 'opacity-100');
                    bottomSheetOverlay.classList.add('invisible', 'opacity-0');
                    document.body.style.overflow = '';
                    bottomSheetContent.removeEventListener('transitionend', handler);
                }, { once: true });
            }

            // Open button click
            if (openModalBtn) { // Always good to check if the element exists before adding listener
                openModalBtn.addEventListener('click', () => {
                    console.log('Open Bottom Sheet button clicked!'); // Log when the click event fires
                    openBottomSheet();
                });
            } else {
                console.error('Element with ID "openModalBtn" not found!');
            }


            // Close when clicking outside the content (on the overlay)
            if (bottomSheetOverlay) {
                bottomSheetOverlay.addEventListener('click', (event) => {
                    if (event.target === bottomSheetOverlay) {
                        console.log('Overlay clicked, attempting to close.');
                        closeBottomSheet();
                    }
                });
            }

            if (selectionOptions.length > 0) {
                selectionOptions.forEach(optionDiv => {
                    optionDiv.addEventListener('click', (event) => {
                        const selectedText = optionDiv.querySelector('p:first-child').textContent;
                        const targetRoute = optionDiv.dataset.route;

                        if (targetRoute) {
                            closeBottomSheet(); 
                            window.location.href = targetRoute; 
                        } else {
                            console.warn(`Clicked option "${selectedText}" has no data-route attribute.`);
                        }
                    });
                });
            }


            // Optional: Close with Escape key
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && bottomSheetOverlay.classList.contains('opacity-100')) {
                    console.log('Escape key pressed, attempting to close.');
                    closeBottomSheet();
                }
            });
        });
    </script>
@endpush