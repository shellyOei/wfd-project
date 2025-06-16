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
                <p class="mr-1">On the line: </p><p class="font-bold text-red-700">5</p><p class="ml-1">person(s)</p>
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
        document.addEventListener('DOMContentLoaded', function() {
            window.Echo.channel('calling-emergency-line').listen('EmergencyCall', (e)=>{
                console.log(e);
                console.log('ha');
            });    
        });
        
    </script>
    
@endsection
