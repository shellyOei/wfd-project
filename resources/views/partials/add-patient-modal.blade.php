@push('head')
    <style>
        /* Optional: Custom utility for iOS safe area if needed and not handled by Tailwind variants */
        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .pb-safe {
                padding-bottom: env(safe-area-inset-bottom);
            }
        }
    </style>
@endpush


    <div class="content text-center max-w-xl bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-4">Welcome to My Page</h1>
        <p class="text-gray-700 mb-6">This is some main content. Click the button below to see the bottom sheet modal!</p>
        <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition-colors duration-200">
            Open Bottom Sheet
        </button>
    </div>

    <div id="bottomSheetOverlay" class="fixed inset-0 bg-black/50 flex justify-center items-end z-[1000] invisible opacity-0 transition-opacity duration-300">
        <div id="bottomSheetContent" class="bg-white w-full max-w-md rounded-t-xl shadow-2xl transform translate-y-full transition-transform duration-300 ease-out-expo pb-safe">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Modal Title</h2>
                <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700 text-3xl leading-none">&times;</button>
            </div>
            <div class="p-5 max-h-[70vh] overflow-y-auto">
                <p class="text-gray-600 mb-4">This is the content that slides up from the bottom.</p>
                <p class="text-gray-600 mb-4">You can put forms, lists, or any other interactive elements here.</p>
                <ul class="list-none p-0 m-0 mb-5">
                    <li class="py-2 border-b border-dotted border-gray-200 last:border-b-0 text-gray-700">Item 1</li>
                    <li class="py-2 border-b border-dotted border-gray-200 last:border-b-0 text-gray-700">Item 2</li>
                    <li class="py-2 last:border-b-0 text-gray-700">Item 3</li>
                </ul>
                <button class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow-md transition-colors duration-200">
                    Perform Action
                </button>
            </div>
        </div>
    </div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openModalBtn = document.getElementById('openModalBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const bottomSheetOverlay = document.getElementById('bottomSheetOverlay');
            const bottomSheetContent = document.getElementById('bottomSheetContent'); // Get content to manipulate transform

            function openBottomSheet() {
                bottomSheetOverlay.classList.remove('invisible', 'opacity-0');
                bottomSheetOverlay.classList.add('visible', 'opacity-100');
                bottomSheetContent.classList.remove('translate-y-full');
                bottomSheetContent.classList.add('translate-y-0');
                document.body.style.overflow = 'hidden'; // Prevent scrolling of background content
            }

            function closeBottomSheet() {
                bottomSheetContent.classList.remove('translate-y-0');
                bottomSheetContent.classList.add('translate-y-full');
                // Wait for the slide-down transition to complete before hiding the overlay
                bottomSheetContent.addEventListener('transitionend', function handler() {
                    bottomSheetOverlay.classList.remove('visible', 'opacity-100');
                    bottomSheetOverlay.classList.add('invisible', 'opacity-0');
                    document.body.style.overflow = ''; // Restore scrolling
                    bottomSheetContent.removeEventListener('transitionend', handler); // Clean up
                }, { once: true });
            }

            // Open button click
            openModalBtn.addEventListener('click', openBottomSheet);

            // Close button click
            closeModalBtn.addEventListener('click', closeBottomSheet);

            // Close when clicking outside the content (on the overlay)
            bottomSheetOverlay.addEventListener('click', (event) => {
                // Check if the click occurred directly on the overlay, not its children
                if (event.target === bottomSheetOverlay) {
                    closeBottomSheet();
                }
            });

            // Optional: Close with Escape key
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && bottomSheetOverlay.classList.contains('opacity-100')) {
                    closeBottomSheet();
                }
            });
        });
    </script>
@endpush