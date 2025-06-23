@include('partials.emergency-options')

<nav class="bottom-navbar fixed bottom-4 left-1/2 -translate-x-1/2 w-[92%] bg-white shadow-xl border-t border-gray-200 z-50 md:hidden rounded-full">
    <div class="flex justify-around items-center h-16">
        <!-- Added space-y-3 to all links for vertical spacing -->
        <div class="nav-item nav-home flex flex-col items-center justify-center text-[var(--gray-inactive)] hover:text-blue-800 transition-colors duration-200 p-2 w-20">
            <a href="{{ route('home')}}" class="">
                <svg width="30" height="30" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.6667 30.75V16.75H21.3333V30.75M4 12.55L17 2.75L30 12.55V27.95C30 28.6926 29.6956 29.4048 29.1539 29.9299C28.6121 30.455 27.8773 30.75 27.1111 30.75H6.88889C6.12271 30.75 5.38791 30.455 4.84614 29.9299C4.30436 29.4048 4 28.6926 4 27.95V12.55Z" stroke="#AAAAAA" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <span class="text-xs mt-1 font-medium">Home</span>
        </div>
        <div class="nav-item nav-appointment flex flex-col items-center justify-center text-[var(--gray-inactive)] hover:text-blue-800 transition-colors duration-200 p-2 w-20">
            <a href="{{ route('user.appointments.index')}}" class="">
                <svg width="26" height="30" viewBox="0 0 26 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <path d="M18.5 4.3665H21.25C21.9793 4.3665 22.6788 4.65097 23.1945 5.15732C23.7103 5.66366 24 6.35042 24 7.0665V25.9665C24 26.6826 23.7103 27.3693 23.1945 27.8757C22.6788 28.382 21.9793 28.6665 21.25 28.6665H4.75C4.02065 28.6665 3.32118 28.382 2.80546 27.8757C2.28973 27.3693 2 26.6826 2 25.9665V7.0665C2 6.35042 2.28973 5.66366 2.80546 5.15732C3.32118 4.65097 4.02065 4.3665 4.75 4.3665H7.5M8.875 1.6665H17.125C17.8844 1.6665 18.5 2.27092 18.5 3.0165V5.7165C18.5 6.46209 17.8844 7.0665 17.125 7.0665H8.875C8.11561 7.0665 7.5 6.46209 7.5 5.7165V3.0165C7.5 2.27092 8.11561 1.6665 8.875 1.6665Z" stroke="#AAAAAA" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <span class="text-xs mt-1 font-medium">Appointment</span>
        </div>
        <div class="nav-item nav-sos flex flex-col items-center justify-center text-[var(--gray-inactive)] hover:text-blue-800 transition-colors duration-200 p-2 w-20">
            <a id="sos-button" class="">
                <svg width="30" height="30" viewBox="0 0 34 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M27.4167 22.1501V25.9001C27.4181 26.2482 27.3444 26.5928 27.2003 26.9118C27.0562 27.2308 26.8448 27.5171 26.5798 27.7525C26.3147 27.9878 26.0017 28.167 25.661 28.2785C25.3202 28.39 24.9591 28.4315 24.6008 28.4001C20.6262 27.9822 16.8082 26.6678 13.4537 24.5626C10.3329 22.6435 7.68689 20.0828 5.70375 17.0626C3.5208 13.8016 2.16231 10.0889 1.73833 6.22512C1.70605 5.87945 1.7485 5.53107 1.86297 5.20215C1.97745 4.87323 2.16144 4.57098 2.40323 4.31465C2.64503 4.05831 2.93933 3.85351 3.26739 3.71327C3.59546 3.57304 3.9501 3.50045 4.30875 3.50012H8.18375C8.8106 3.49415 9.41831 3.70897 9.8936 4.10454C10.3689 4.5001 10.6793 5.04943 10.7671 5.65012C10.9306 6.8502 11.234 8.02853 11.6712 9.16262C11.845 9.61003 11.8826 10.0963 11.7796 10.5637C11.6766 11.0312 11.4373 11.4603 11.09 11.8001L9.44958 13.3876C11.2883 16.5171 13.9658 19.1082 17.1996 20.8876L18.84 19.3001C19.1912 18.964 19.6346 18.7324 20.1176 18.6327C20.6006 18.533 21.1031 18.5694 21.5654 18.7376C22.7373 19.1608 23.9549 19.4543 25.195 19.6126C25.8224 19.6983 26.3955 20.0041 26.8051 20.472C27.2147 20.9398 27.4324 21.5371 27.4167 22.1501Z" stroke="#AAAAAA" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.5 5.37502V8.20836M25.5 11.0417H25.5071M24.2887 1.73419L18.2892 11.75C18.1655 11.9642 18.1 12.2071 18.0993 12.4545C18.0986 12.7018 18.1627 12.9451 18.2852 13.16C18.4077 13.3749 18.5843 13.554 18.7975 13.6794C19.0107 13.8049 19.2531 13.8723 19.5004 13.875H31.4996C31.7469 13.8723 31.9892 13.8049 32.2024 13.6794C32.4156 13.554 32.5923 13.3749 32.7148 13.16C32.8373 12.9451 32.9014 12.7018 32.9007 12.4545C32.9 12.2071 32.8345 11.9642 32.7108 11.75L26.7112 1.73419C26.585 1.52602 26.4072 1.3539 26.195 1.23445C25.9828 1.115 25.7435 1.05225 25.5 1.05225C25.2565 1.05225 25.0171 1.115 24.805 1.23445C24.5928 1.3539 24.415 1.52602 24.2887 1.73419Z" stroke="#AAAAAA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <span class="text-xs mt-1 font-medium">SOS</span>
        </div>
        <div class="nav-item nav-book flex flex-col items-center justify-center text-[var(--gray-inactive)] hover:text-blue-800 transition-colors duration-200 p-2 w-20">
            <a href="{{ route('doctors.filter')}}" class="">
                <svg width="30" height="30" viewBox="0 0 31 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <path d="M18.0832 2.5H7.74984C7.06469 2.5 6.40761 2.76339 5.92314 3.23223C5.43868 3.70107 5.1665 4.33696 5.1665 5V25C5.1665 25.663 5.43868 26.2989 5.92314 26.7678C6.40761 27.2366 7.06469 27.5 7.74984 27.5H23.2498C23.935 27.5 24.5921 27.2366 25.0765 26.7678C25.561 26.2989 25.8332 25.663 25.8332 25V10M18.0832 2.5L25.8332 10M18.0832 2.5V10H25.8332M15.4998 22.5V15M11.6248 18.75H19.3748" stroke="#AAAAAA" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <span class="text-xs mt-1 font-medium">Booking</span>
        </div>
         <div class="nav-item nav-profile flex flex-col items-center justify-center text-[var(--gray-inactive)] hover:text-blue-800 transition-colors duration-200 p-2 w-20">
            <a href="{{ route('user.profile') }}" class="">
                <svg width="30" height="30" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M28.3333 28.875V26.125C28.3333 24.6663 27.7363 23.2674 26.6736 22.2359C25.6109 21.2045 24.1695 20.625 22.6666 20.625H11.3333C9.8304 20.625 8.38906 21.2045 7.32635 22.2359C6.26365 23.2674 5.66663 24.6663 5.66663 26.125V28.875M22.6666 9.625C22.6666 12.6626 20.1296 15.125 17 15.125C13.8703 15.125 11.3333 12.6626 11.3333 9.625C11.3333 6.58743 13.8703 4.125 17 4.125C20.1296 4.125 22.6666 6.58743 22.6666 9.625Z" stroke="#AAAAAA" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <span class="text-xs mt-1 font-medium">Profile</span>
        </div>
    </div>
</nav>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // document.addEventListener('DOMContentLoaded', () => {
            // const navItems = document.querySelectorAll('.nav-item');

            // function highlightActiveMenu() {
            //     const currentHash = window.location.hash.substring(1) || 'home';

            //     navItems.forEach(item => {
            //         // Get the target page from the <a> tag's href inside the nav-item div
            //         const link = item.querySelector('a');
            //         if (link) {
            //             const targetPage = link.getAttribute('href').substring(1); // Get "home", "appointment" etc.

            //             // Remove active classes from all items first, using variable syntax for colors
            //             item.classList.remove('font-bold', 'bg-[var(--primary-bg-light)]');
            //             item.classList.add('text-[var(--text-default-color)]'); // Set default color

            //             // If the current hash matches the target page, add active classes
            //             if (currentHash === targetPage) {
            //                 item.classList.add('font-bold', 'bg-[var(--primary-bg-light)]'); // Add active background and font weight
            //                 item.classList.remove('text-[var(--text-default-color)]'); // Remove default color
            //                 item.classList.add('text-[var(--primary-color)]'); // Add active text/icon color
            //             }
            //         }
            //     });
            // }

            // highlightActiveMenu();

            // window.addEventListener('hashchange', highlightActiveMenu);
        });


        document.getElementById('sos-button').addEventListener('click', () => {
            document.getElementById('emergency-bg').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('emergency-modal').classList.remove('bottom-[-1000px]');
                document.getElementById('emergency-modal').classList.add('bottom-0', 'sm:bottom-1/2');    
                document.body.style.maxHeight = '100vh';
                document.body.style.overflow = 'hidden';
            }, 100);
            
            // document.getElementById('emergency-bg').style.opacity = 1;
        });
    </script>
@endpush
