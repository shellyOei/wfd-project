@extends('layout')

@section('head')
    {{-- leaflet js --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
     
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

    {{-- @vite(['']) --}}

    @auth
        {{-- <input type="hidden" id="loggedInUserId" value="{{ Auth::id() }}"> --}}
    @endauth

    <style>

        #map { height: 200px; }

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
    <div class="flex flex-col text-center  items-center h-screen pt-[135px]">
        <div class="h-[77%] flex flex-col justify-between">
            <div>
                <h2 class="font-bold text-xl mb-2">GAWAT DARURAT</h2>
                <p id="description">Menghubungi pusat bantuan dalam...</p>
            </div>


            <div id="visualization">
                <h1 class="text-9xl font-bold" id="countdown-container">5</h1>
                <p>detik</p>
            </div>

            <div id="button-area" class="flex flex-col items-center">
                
                <button id="button-action" class="relative bg-red-600 hover:bg-red-700 rounded-full h-[100px] w-[100px] mb-4">
                    <div class="absolute w-[70px] rounded bg-gray-200 h-2 rotate-[-45deg] top-[50px] left-[15px]"></div>
                    <div class="absolute w-[70px] rounded bg-gray-200 h-2 rotate-[45deg] top-[50px] left-[15px]"></div>
                </button>
                <p id="button-desc" class="uppercase font-bold">batalkan</p>
                
            </div>
        </div>
    </div>
        
@endsection

@section('script')
    <script>
        let countdownValue = 6;
        const userIdInput = document.getElementById('loggedInUserId');
        const userId = userIdInput ? userIdInput.value : null;
        const uuid = crypto.randomUUID();

        // areas to temper with
        const description = document.getElementById('description');
        const visualization = document.getElementById('visualization');
        const buttonArea = document.getElementById('button-area');
        const buttonActElement = document.getElementById('button-action');
        const buttonDescElement = document.getElementById('button-desc');

        // for leafletjs
        const ambulanceCoordinate = [-7.363559622969599, 112.72226191534286]; // lat, lng
        let map; 
        let userMarker; 
        let ambulanceMarker;
        let pathLine; 
        let simulationInterval;



        document.addEventListener('DOMContentLoaded', () => {
            
            intervalId = setInterval(updateCountdown, 1100); 

            window.Echo.channel('calling-emergency-line').listen('EmergencyCall', (e)=>{


            });    
            
        });
        

        async function updateCountdown () {
            countdownValue--;

             if (countdownValue < 0) {
                clearInterval(intervalId); 
                
                try {
                    const response = await fetch('{{ route('user.emergency.request') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ 
                            uuid : uuid
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        waitingDispatcher()
                        // ambulanceTracking()
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Call Failed.",
                            text: response.message,
                        });
                        console.log(response)
                    }
                } catch (error) {
                    Swal.fire({
                        icon: "error",
                        title: "Call Failed.",
                        text: error,
                    });
                    console.log(error);
                }


            } else {
                document.getElementById('countdown-container').innerHTML = countdownValue;
            }
        }

        function getLocation() {
            if (navigator.geolocation) {
                const options = {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                };
                navigator.geolocation.getCurrentPosition(showPosition, showError, options);
            } else {
                ambulanceTracking(null);
            }
        }

        // success callback
        function showPosition(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            const userLocation = [userLat, userLng];

            console.log(userLocation);
            ambulanceTracking(userLocation);
        }


        // fail callback
        function showError(error) {
            let errorMessage = "";
            switch(error.code) {
                // case error.PERMISSION_DENIED:
                //     errorMessage = "User denied the request for Geolocation. Cannot track your location.";
                //     break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = "Location information is unavailable. Cannot track your location.";
                    break;
                case error.TIMEOUT:
                    errorMessage = "The request to get user location timed out. Cannot track your location.";
                    break;
                case error.UNKNOWN_ERROR:
                    errorMessage = "An unknown error occurred. Cannot track your location.";
                    break;
            }

            if (errorMessage !== "") {
                Swal.fire({
                    title: "GPS Service Rejected.",
                    text: errorMessage,
                    icon: "error"
                });
            }
            
        }



        function waitingDispatcher () {
            visualization.innerHTML = `<span class="loader"></span>`;
            description.innerHTML = 'Mohon menunggu. Ada _ orang sebelum anda.';
            getLocation();
        }


        


        function ambulanceTracking (userLocation) {
            visualization.innerHTML = `<div id="map"></div>`;
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
            userMarker = L.marker([userLocation[0], userLocation[1]], { icon: redIcon }).addTo(map);
            // create a red polyline from an array of LatLng points
            var latlngs = [
                ambulanceCoordinate,
                userLocation
            ];

            var polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);

            // zoom the map to the polyline
            map.fitBounds(polyline.getBounds());


            description.innerHTML = 'Ambulance sedang menuju tempat anda.';




            // ambulance simulation
            let currentAmbulanceLat = ambulanceCoordinate[0];
            let currentAmbulanceLng = ambulanceCoordinate[1];

            const targetLat = userLocation[0];
            const targetLng = userLocation[1];

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

            simulationInterval = setInterval(() => {
                stepCount++;

                // Move the ambulance
                currentAmbulanceLat += latStep;
                currentAmbulanceLng += lngStep;

                const newAmbulancePos = [currentAmbulanceLat, currentAmbulanceLng];
                ambulanceMarker.setLatLng(newAmbulancePos); // Update marker position

                // Update the path line
                if (pathLine) {
                    pathLine.setLatLngs([newAmbulancePos, userLocation]);
                } else {
                    pathLine = L.polyline([newAmbulancePos, userLocation], {color: 'red', weight: 5, opacity: 0.7}).addTo(map);
                }

                // Check if ambulance is very close to the user's location
                // Using Leaflet's distanceTo for more accurate "closeness" check
                const distance = L.latLng(newAmbulancePos).distanceTo(L.latLng(userLocation));
                const proximityThreshold = 10; // meters

                if (distance < proximityThreshold || stepCount >= totalSteps) {
                    clearInterval(simulationInterval); // Stop the simulation
                    simulationInterval = null;
                    description.innerHTML = 'Ambulance telah sampai!';
                    ambulanceMarker.setLatLng(userLocation); // Snap to the exact user location
                    if (pathLine) {
                        pathLine.setLatLngs([userLocation, userLocation]); // Make path effectively disappear or just a dot
                    }
                    
                } else {
                    description.innerHTML = `Ambulance is en route! Distance: ${distance.toFixed(0)} meters.`;
                }

                // Center map to fit both markers as ambulance moves
                const bounds = L.latLngBounds([newAmbulancePos, userLocation]);
                map.fitBounds(bounds, { padding: [50, 50] });

            }, intervalMilliseconds); // Update every 1 second
        }


    </script>
@endsection
                        