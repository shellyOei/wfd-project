@extends('layout')

@section('head')
    {{-- leaflet js --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
     
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

    <style>

        

        .loader, .loader:before, .loader:after {
            border-radius: 50%;
            width: 2.5em;
            height: 2.5em;
            animation-fill-mode: both;
            animation: bblFadInOut 1.8s infinite ease-in-out;
        }
        .loader {
            color: var(--blue1);
            font-size: 7px;
            position: relative;
            text-indent: -9999em;
            transform: translateZ(0);
            animation-delay: -0.16s;
        }
        .loader:before,
        .loader:after {
            content: '';
            position: absolute;
            top: 0;
        }
        .loader:before {
            left: -3.5em;
            animation-delay: -0.32s;
        }
        .loader:after {
            left: 3.5em;
        }

        @keyframes bblFadInOut {
            0%, 80%, 100% { box-shadow: 0 2.5em 0 -1.3em }
            40% { box-shadow: 0 2.5em 0 0 }
        }
            
    </style>

@endsection

@section('content')
    <div class="flex flex-col text-center  items-center h-screen pt-[90px] max-[400px]:pt-[55px] px-4 lg:px-0">
        <div class="h-[95%] flex flex-col justify-between">
            <div>
                <h2 class="font-bold text-xl mb-4">GAWAT DARURAT</h2>
                <p id="description">Menghubungi pusat darurat dalam...</p>
            </div>


            <div class="!h-[50%] py-auto my-auto flex flex-col justify-center items-center">
                <p id="call-duration" class="mb-4"></p>
                <div id="visualization" class="text-center">
                    <h1 class="text-9xl font-bold" id="countdown-container">5</h1>
                    <p>detik</p>
                </div>
            </div>

            <div id="button-area" class="flex flex-col items-center h-auto min-[400px]:mb-8">
                
                <button onclick="window.history.back()" id="button-action" class="relative bg-red-600 hover:bg-red-700 rounded-full h-[100px] w-[100px] mb-4 drop-shadow-4xl">
                    <div class="absolute w-[70px] rounded bg-gray-200 h-2 rotate-[-45deg] top-[50px] left-[15px] drop-shadow-xl"></div>
                    <div class="absolute w-[70px] rounded bg-gray-200 h-2 rotate-[45deg] top-[50px] left-[15px] drop-shadow-xl"></div>
                </button>
                <p id="button-desc" class="uppercase font-bold">batalkan</p>
            </div>
        </div>
    </div>
        
@endsection

@section('script')
    <script>
        let countdownValue = 6;
        let totalCallDuration = 0;
        // const userIdInput = document.getElementById('loggedInUserId');
        // const userId = userIdInput ? userIdInput.value : null;
        // const uuid = crypto.randomUUID();

        // areas to temper with
        const description = document.getElementById('description');
        const visualization = document.getElementById('visualization');
        const buttonArea = document.getElementById('button-area');
        const buttonActElement = document.getElementById('button-action');
        const buttonDescElement = document.getElementById('button-desc');
        const callDurationElement = document.getElementById('call-duration');

        // for leafletjs
        const ambulanceCoordinate = [-7.363559622969599, 112.72226191534286]; // lat, lng
        let userCoordinate = [-7.288291535268349, 112.67558541723966]; // lat, lng -> set default for simulation
        let map; 
        let userMarker; 
        let ambulanceMarker;
        let pathLine; 
        let simulationInterval;



        document.addEventListener('DOMContentLoaded', () => {
            
            intervalId = setInterval(updateCountdown, 1100); 

            // window.Echo.channel('calling-emergency-line').listen('EmergencyCall', (e)=>{


            // });    

            const closeButtons = document.querySelectorAll('.close-emergency-modal');

            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    setTimeout(() => {
                        document.getElementById('emergency-modal').classList.remove('!bottom-0');
                    }, 100);
                    
                    // document.getElementById('emergency-bg').style.opacity = 0;
                    setTimeout(() => {
                        document.getElementById('emergency-bg').classList.add('hidden');
                    }, 550);
                }) 
            });
            
        });
        

        async function updateCountdown () {
            countdownValue--;

             if (countdownValue < 0) {
                clearInterval(intervalId);
                waitingDispatcher();

            } else {
                document.getElementById('countdown-container').innerHTML = countdownValue;
            }
        }

        function getLocation() {
            return new Promise((resolve, reject) => {
                if (navigator.geolocation) {
                    const options = {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    };
                    navigator.geolocation.getCurrentPosition(
                        (position) => showPosition(position, resolve), 
                        (error) => showError(error, reject),           
                        options
                    );
                }   else {
                    // Geolocation is not supported
                    const error = new Error();
                    error.code = "NOT_SUPPORTED";
                    showError(error, reject); 
                }
            });
        }

        // success callback
        function showPosition(position, resolveCallback) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            const userLocation = [userLat, userLng];

            console.log(userLocation);
            resolveCallback({'userLocation' : userLocation}); 
        }


        // fail callback
        function showError(error, rejectCallback) {
            let errorMessage = "";
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    // errorMessage = "User denied the request for Geolocation. Cannot track your location.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = "Location information is unavailable. Cannot track your location.";
                    break;
                case error.TIMEOUT:
                    errorMessage = "The request to get user location timed out. Cannot track your location.";
                    break;
                case error.UNKNOWN_ERROR:
                    errorMessage = "An unknown error occurred. Cannot track your location.";
                    break;
                case "NOT_SUPPORTED":
                    errorMessage = "Your browser does not support geolocation. Cannot track your location.";
                    break;
                default:
                    errorMessage = error.message || "An unknown error occurred. Cannot track your location.";
                    break;
            }

            if (errorMessage !== "") {
                Swal.fire({
                    title: "Gagal membaca lokasi GPS.",
                    text: 'Mohon beritahu lokasi anda kepada staff kami.',
                    icon: "error"
                }).then(() => {
                    rejectCallback(error)
                });
            }

            
            
        }



        function waitingDispatcher () {
            visualization.innerHTML = `<span class="loader"></span>`;
            description.innerHTML = 'Mohon menunggu. Ada 1 orang sebelum anda.';

            getLocation()
                .then(locationData => {
                    userCoordinate = locationData.userLocation;
                    
                // })
                // .catch(error => {
                //     Swal.fire({
                //     title: "Fail to read GPS location.",
                //     text: error,
                //     icon: "error"
                // })
                }).finally(() => {
                    callOnGoing()
                });
        }


        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;

            const formattedMinutes = String(minutes).padStart(2, '0');
            const formattedSeconds = String(remainingSeconds).padStart(2, '0');

            return `${formattedMinutes}:${formattedSeconds}`;
        }



        function callOnGoing() {
            visualization.innerHTML = `<img class="grow" src="{{ asset('assets/emergency/emergency_profile.png') }}" alt="Staff EW">`;
            description.innerHTML = 'Anda terhubung dengan pusat darurat kami.';

            callDurationElement.textContent = formatTime(totalCallDuration);

            timerInterval = setInterval(() => {
                totalCallDuration++; 
                callDurationElement.textContent = formatTime(totalCallDuration); 
            }, 1000);

            buttonDescElement.classList.add('opacity-0');
            buttonActElement.innerHTML = `<img class="w-full h-full object-contain drop-shadow-xl" src="{{ asset('assets/emergency/Phone.png') }}" alt="hang up">`;

             setTimeout(() => {
                ambulanceTracking();
            }, 10000);
            
        }
        


        function ambulanceTracking () {


            visualization.innerHTML = `<div id="map" class="h-[45vh] sm:h-[40vh] w-[90vw] md:w-[40vw]"></div>`;
            map = L.map('map').setView([ambulanceCoordinate[0], ambulanceCoordinate[1]], 14);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            

            var redIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const ambulanceIcon = L.icon({
                iconUrl: 'https://pngimg.com/uploads/ambulance/ambulance_PNG18.png', 
                iconSize: [40, 40], 
                iconAnchor: [20, 40], 
                popupAnchor: [0, -35] 
            });


            ambulanceMarker = L.marker([ambulanceCoordinate[0], ambulanceCoordinate[1]], { icon: ambulanceIcon }).addTo(map);
            userMarker = L.marker([userCoordinate[0], userCoordinate[1]], { icon: redIcon }).addTo(map);
            
            var latlngs = [
                ambulanceCoordinate,
                userCoordinate
            ];

            var polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);

            // zoom the map to the polyline
            map.fitBounds(polyline.getBounds());


            description.innerHTML = 'Ambulance sedang menuju tempat anda.';




            // ambulance simulation
            let currentAmbulanceLat = ambulanceCoordinate[0];
            let currentAmbulanceLng = ambulanceCoordinate[1];

            const targetLat = userCoordinate[0];
            const targetLng = userCoordinate[1];

            // Calculate steps for simulation (e.g., 20 seconds to reach destination)
            // You can make this dynamic based on actual distance if needed for varying speeds
            const totalSimulationSeconds = 20; // Ambulance will take 20 seconds to reach destination
            const intervalMilliseconds = 1000; // Update every 1 second
            const totalSteps = totalSimulationSeconds * (1000 / intervalMilliseconds);

            const latStep = (targetLat - currentAmbulanceLat) / totalSteps;
            const lngStep = (targetLng - currentAmbulanceLng) / totalSteps;

            let stepCount = 0;

            // Clear previous interval if any
            if (simulationInterval) {
                clearInterval(simulationInterval);
            }

            navigator.vibrate(1500);
            var audio = new Audio('{{ asset('assets/emergency/ambulance_en_route.mp3') }}');
            audio.play();
            description.innerHTML = `<p>Ambulance dalam perjalanan</p>
                                    <p class="text-gray-400">Anda masih terhubung dengan pusat darurat kami.</p>   
                                    `;

            simulationInterval = setInterval(() => {
                stepCount++;

                // Move the ambulance
                currentAmbulanceLat += latStep;
                currentAmbulanceLng += lngStep;

                const newAmbulancePos = [currentAmbulanceLat, currentAmbulanceLng];
                ambulanceMarker.setLatLng(newAmbulancePos); // Update marker position

                // Update the path line
                if (pathLine) {
                    pathLine.setLatLngs([newAmbulancePos, userCoordinate]);
                } else {
                    pathLine = L.polyline([newAmbulancePos, userCoordinate], {color: 'red', weight: 5, opacity: 0.7}).addTo(map);
                }

                // Check if ambulance is very close to the user's location
                // Using Leaflet's distanceTo for more accurate "closeness" check
                const distance = L.latLng(newAmbulancePos).distanceTo(L.latLng(userCoordinate));
                const proximityThreshold = 10; // meters

                const remainingMinutes = Math.round((distance / 60) / 50); 
                const currentTime = new Date();
                const arrivalTime = new Date(currentTime.getTime() + remainingMinutes * 60 * 1000); // Add minutes in milliseconds
                const formattedArrivalTime = arrivalTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                if (distance < proximityThreshold || stepCount >= totalSteps) {
                    clearInterval(simulationInterval); // Stop simulation
                    simulationInterval = null;

                    totalCallDuration = 0

                    description.innerHTML = `<p>Ambulance telah sampai!</p>
                                             <p class="text-gray-400">Sekarang anda terhubung dengan ambulance kami</p>   
                                            `;

                    buttonArea.innerHTML = `<p class="text-lg font-bold">Tenaga medis telah tiba!</p>`;

                    navigator.vibrate(1500);
                    var audio = new Audio('{{ asset('assets/emergency/ambulance_sampai.mp3') }}');
                    audio.play();
                                            
                    ambulanceMarker.setLatLng(userCoordinate); 
                    if (pathLine) {
                        pathLine.setLatLngs([userCoordinate, userCoordinate]); 
                    }

                    setTimeout(() => {
                        window.location.href = "{{ route('user.dashboard') }}";
                    }, 10000);
                    
                } else {
                    buttonArea.innerHTML = `<p>Estimasi kedatangan:</p>
                                            <p class="font-bold text-lg my-2">${formattedArrivalTime}</p>
                                            <p>${remainingMinutes} menit lagi</p>
                                            `
                }

                // Center map to fit both markers as ambulance moves
                const bounds = L.latLngBounds([newAmbulancePos, userCoordinate]);
                map.fitBounds(bounds, { padding: [50, 50] });

            }, intervalMilliseconds); 
        }


    </script>
@endsection
                        