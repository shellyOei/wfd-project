@extends('admin.layout')

@section('title', 'Emergency')
@section('page-title', 'Emergency Dashboard')

@section('head')

    @vite(['resources/js/zego_call.js'])

@endsection

@section('content')
    <div class="flex flex-col h-[93vh]">
        {{-- incoming calls --}}
        <div class="h-fit pb-6">
            <p class="text-xl font-semibold text-gray-700">Incoming Calls</p>
            <hr class="h-1 w-full bg-gray-500 mb-2">
            

            {{-- incoming calls from people --}}
            <div class="flex">
                <p class="mr-1">On the line: </p><p id="on-line-count-container" class="font-bold"></p><p class="ml-1">person(s)</p>
                <button onclick="nextCall()" class="ml-auto bg-orange-400 hover:bg-orange-500 text-white px-5 py-2 rounded">☏ Next/End Call</button>    
            </div>
            

        </div>

        <hr class="h-0.5 w-full bg-gray-300 drop-shadow-xl mb-2">
        

        {{-- control panel --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start mt-2">

            {{-- patient data --}}
            <form class="space-y-4">
                <div class="flex items-center !border-b-2 border-gray-500">
                    <p class="text-xl font-semibold text-gray-700 mr-1">Patient Biodata :</p>
                </div>
                <div class="flex flex-col">
                    <label for="name">Name:</label>
                    <input type="text" name="name">
                </div>

                <div class="flex flex-col">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" name="dob">
                </div>

                <div class="flex flex-col">
                    <label for="addr">Address:</label>
                    <input type="text" name="addr">
                </div>

                <div class="flex flex-col">
                    <label for="sex">Sex:</label>
                    <input type="text" name="sex">
                </div>

                <div class="flex flex-col">
                    <label for="blood_type">Blood Type / Rhesus:</label>
                    <div class="w-full flex items-center">
                        <select name="blood_type">
                            <option selected value="">Unknown</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                        <p class="mx-1.5 font-semibold text-2xl">/</p>
                        <select name="rhesus">
                            <option selected value="">Unknown</option>
                            <option value="+">+</option>
                            <option value="-">-</option>
                        </select>
                    </div>
                </div>
                
                <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">Dispatch Ambulance</button>
            </form>

            {{-- ambulance control panel --}}
            <div class="grid grid-rows-3 gap-1 h-full items-start">
                <div>
                    <div class="flex items-center !border-b-2 border-gray-500">
                        <p class="text-xl font-semibold text-gray-700 mr-1">Call status :</p><p class="text-lg">ONLINE</p>
                        
                    </div>
                    <div>
                        <div>
                            <label for="roomID">Room ID:</label>
                            <input type="text" id="roomID" value="testRoom123">
                            <button id="toggleMic" disabled>Toggle Mic</button>
                            {{-- <button id="joinButton">Join Call</button>
                            <button id="leaveButton" disabled>Leave Call</button> --}}
                        </div>

                        <div id="room-controls">
                            <button id="toggleSpeaker" disabled>Toggle Speaker</button>
                        </div>

                        <audio id="local-audio" autoplay muted></audio>
                        <div id="remote-streams"></div>
                    </div>
                </div>
                
                <div class="row-span-2">
                    <div class="flex items-center !border-b-2 border-gray-500">
                        <p class="text-xl font-semibold text-gray-700 mr-1">GPS Location</p>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        const callCountContainer = document.getElementById('on-line-count-container');

        // for patient information
        const nameContainer = document.querySelector('input[name="name"]');
        const sexContainer = document.querySelector('input[name="sex"]');
        const dobContainer = document.querySelector('input[name="dob"]');
        const bloodTypeContainer = document.querySelector('select[name="blood_type"]');
        const rhesusContainer = document.querySelector('select[name="rhesus"]');
        const addrContainer = document.querySelector('input[name="addr"]');

        document.addEventListener('DOMContentLoaded', function() {

            refreshCount();

            window.Echo.channel('calling-emergency-line').listen('EmergencyCall', (e)=>{
                

                // refresh count
                refreshCount();



            });    

        });

        async function refreshCount () {
            const data = await fetchData('{{ route('admin.emergency.count') }}');
            // const count = data;
            // console.log(count);

            if (data === null) {
                callCountContainer.innerHTML = `ERROR`;
                callCountContainer.classList.add('text-red-600');
            } else {
                callCountContainer.innerHTML = data;    
                callCountContainer.classList.add('text-red-600');

                if (data === 0) {
                    callCountContainer.classList.remove('text-red-600');
                }
            }

        }
        

        async function fetchData(url) {
               
            try {
                
                const response = await fetch(url);

                if (!response.ok) {
                    return null;
                    const errorText = await response.text();
                    throw new Error(`HTTP error! Status: ${response.status} - ${errorText}`);
                }

                const data = await response.json();
                // console.log(data);
                return data;

            } catch (error) {
                console.error('Error fetching data:', error);
                return null; 
            } 
        }


        window.nextCall = async function () {
            const data = await fetchData('{{ route('admin.emergency.next') }}');
            const count = data['onLineCount'];
            const identity = data['nextCall'];
            console.log(identity.frontend_uuid)

            if (identity.user && identity.user.patients) {
                const profile = identity.user.patients[0];
                console.log(profile);
                // take care of input fields
                nameContainer.value = profile.name ?? "";
                sexContainer.value = profile.sex ?? "";
                dobContainer.value = profile.date_of_birth ?? "";
                bloodTypeContainer.value = profile.blood_type ?? "";
                rhesusContainer.value = profile.rhesus_factor ?? "";
            } 

            // connect call here
            initZegoClient(identity.frontend_uuid);
            
            
            // take care of count
            if (count === null) {
                callCountContainer.innerHTML = `ERROR`;
                callCountContainer.classList.add('text-red-600');
            } else {
                callCountContainer.innerHTML = count;    
                callCountContainer.classList.add('text-red-600');

                if (count === 0) {
                    callCountContainer.classList.remove('text-red-600');
                }
            }

            

        }




        async function fetchZegoToken($frontendUuid) {
            try {
                const response = await fetch('{{ route('zegocloud.token') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        frontend_uuid : $frontendUuid
                    })
                });
                
                const data = await response.json();
                if (response.ok) {
                    currentUserID = data.userID;
                    currentUserName = data.userName;
                    currentAppID = data.appID;
                    currentToken = data.token;
                    console.log('ZegoCloud token fetched:', data);
                    return true;
                } else {
                    console.error('Failed to fetch ZegoCloud token:', data.error);
                    alert('Failed to fetch ZegoCloud token: ' + data.error);
                    return false;
                }
            } catch (error) {
                console.error('Error fetching ZegoCloud token:', error);
                alert('Error fetching ZegoCloud token. Check console for details.');
                return false;
            }
        }


       

    </script>
    
@endsection
