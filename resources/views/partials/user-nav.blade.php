<nav class="bottom-navbar fixed bottom-4 left-1/2 -translate-x-1/2 w-[92%] bg-white shadow-xl border-t border-gray-200 z-50 md:hidden rounded-full">
    <div class="flex justify-around items-center h-16">
        <!-- Added space-y-3 to all links for vertical spacing -->
        <div class="nav-item nav-home flex flex-col items-center justify-center text-gray-500 hover:text-blue-800 transition-colors duration-200 p-2 w-20">
            <a href="#" class="">
                <i class="fa-solid fa-house fa-lg"></i>
            </a>
            <span class="text-xs mt-1 font-medium">Home</span>
        </div>
        <div class="nav-item nav-appointment flex flex-col items-center justify-center text-gray-500 hover:text-blue-800 transition-colors duration-200 p-2 w-20">
            <a href="#" class="">
                <i class="fa-solid fa-clipboard-list fa-xl"></i>
            </a>
            <span class="text-xs mt-1 font-medium">Appointment</span>
        </div>
        <div class="nav-item nav-sos flex flex-col items-center justify-center text-gray-500 hover:text-blue-800 transition-colors duration-200 p-2 w-20">
            <a href="#" class="">
                <i class="fa-solid fa-phone fa-xl"></i>
            </a>
            <span class="text-xs mt-1 font-medium">SOS</span>
        </div>
        <div class="nav-item nav-book flex flex-col items-center justify-center text-gray-500 hover:text-blue-800 transition-colors duration-200 p-2 w-20">
            <a href="#" class="">
                <i class="fa-solid fa-book-medical fa-xl"></i>
            </a>
            <span class="text-xs mt-1 font-medium">Booking</span>
        </div>
         <div class="nav-item nav-profile flex flex-col items-center justify-center text-gray-500 hover:text-blue-800 transition-colors duration-200 p-2 w-20">
            <a href="#" class="">
                <i class="fa-solid fa-user fa-xl"></i>
            </a>
            <span class="text-xs mt-1 font-medium">Profile</span>
        </div>
    </div>
</nav>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('DOMContentLoaded', () => {
            const navItems = document.querySelectorAll('.nav-item');

            function highlightActiveMenu() {
                const currentHash = window.location.hash.substring(1) || 'home';

                navItems.forEach(item => {
                    // Get the target page from the <a> tag's href inside the nav-item div
                    const link = item.querySelector('a');
                    if (link) {
                        const targetPage = link.getAttribute('href').substring(1); // Get "home", "appointment" etc.

                        // Remove active classes from all items first, using variable syntax for colors
                        item.classList.remove('font-bold', 'bg-[var(--primary-bg-light)]');
                        item.classList.add('text-[var(--text-default-color)]'); // Set default color

                        // If the current hash matches the target page, add active classes
                        if (currentHash === targetPage) {
                            item.classList.add('font-bold', 'bg-[var(--primary-bg-light)]'); // Add active background and font weight
                            item.classList.remove('text-[var(--text-default-color)]'); // Remove default color
                            item.classList.add('text-[var(--primary-color)]'); // Add active text/icon color
                        }
                    }
                });
            }

            highlightActiveMenu();

            window.addEventListener('hashchange', highlightActiveMenu);
        });
        });
    </script>
@endpush
