@extends('layout')

@section('head')

    @auth
        {{-- <input type="hidden" id="loggedInUserId" value="{{ Auth::id() }}"> --}}
    @endauth

    <style>

        .loader, .loader:before, .loader:after {
            border-radius: 50%;
            width: 2.5em;
            height: 2.5em;
            animation-fill-mode: both;
            animation: bblFadInOut 1.8s infinite ease-in-out;
        }
        .loader {
            color: #FFF;
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



        function waitingDispatcher () {
            visualization.innerHTML = `<span class="loader"></span>`;
            description.innerHTML = 'Mohon menunggu. Ada _ orang sebelum anda.';
        }



        document.addEventListener('DOMContentLoaded', () => {
            
            intervalId = setInterval(updateCountdown, 1100); 
            
        });
    </script>
@endsection
                        