@extends('admin.layout')

@section('title', 'Emergency')
@section('page-title', 'Emergency Dashboard')

@section('head')
    
@endsection

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 h-[83vh]">
        {{-- incoming calls --}}
        <div class="h-full pb-6">
            <p class="text-xl font-semibold text-gray-700">Incoming Calls</p>
            <hr class="h-1 w-full bg-gray-500 mb-2">
            

            {{-- incoming calls from people --}}
            <div class="flex">
                <p class="mr-1">On the line: </p><p id="on-line-count-container" class="font-bold"></p><p class="ml-1">person(s)</p>
                <button class="ml-auto bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">☏ Next Call</button>    
            </div>
            

        </div>

        {{-- control panel --}}
        <div class="">

            
            <div>

            </div>
            <div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        const callCountContainer = document.getElementById('on-line-count-container');

        document.addEventListener('DOMContentLoaded', function() {

            refreshCount();

            window.Echo.channel('calling-emergency-line').listen('EmergencyCall', (e)=>{
                

                // refresh count
                refreshCount();


            });    

        });

        async function refreshCount () {
            const data = await fetchData('{{ route('admin.emergency.count') }}');
            const count = data;
            console.log(count);

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
    </script>
    
@endsection
