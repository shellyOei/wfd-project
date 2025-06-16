@extends('layout')

@section('content')
<div class="max-w-md mx-auto p-6">
    <h1 class="text-xl font-semibold text-center mb-6">Upload Histori Rekam Medis</h1>

    {{-- <div class="border-2 border-dashed border-blue-300 rounded-lg bg-blue-50 p-6 text-center mb-4">
        <div class="flex justify-center mb-2">
            <img src="{{ asset('icons/cloud-upload.svg') }}" class="w-10 h-10" alt="upload">
        </div>
        <p class="text-gray-700">Drag & drop files or <span class="text-blue-600 font-semibold cursor-pointer">Browse</span></p>
        <p class="text-sm text-gray-500">Supported formats: JPEG, PNG, PDF, JPG</p>
    </div> --}}
{{-- 
    <div class="mb-4">
        <p class="text-sm font-medium mb-2">Uploading - 3/3 files</p>

        @foreach (['Andrew_passport.png', 'LOR_Harvard University.PDF', 'Andrew_SOP.PDF'] as $filename)
        <div class="mb-2">
            <div class="flex items-center justify-between text-sm bg-gray-100 px-3 py-2 rounded">
                <span>{{ $filename }}</span>
                <button class="text-gray-400 hover:text-red-600">&times;</button>
            </div>
            <div class="h-1 bg-gradient-to-r from-cyan-400 to-blue-600 rounded-full mt-1"></div>
        </div>
        @endforeach
    </div> --}}

    <div
    x-data="fileUploadComponent()"
    x-on:drop.prevent="handleDrop($event)"
    x-on:dragover.prevent
    class="max-w-md mx-auto border-2 border-dashed border-blue-400 rounded-xl p-6 text-center cursor-pointer hover:bg-blue-50 transition"
    @click="$refs.fileInput.click()"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-blue-500" fill="none"
         viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 16V4m0 0L3 8m4-4l4 4m5 0h4a2 2 0 012 2v9a2 2 0 01-2 2h-4m-4 0H7a2 2 0 01-2-2v-5"/>
    </svg>

    <p class="mt-2 text-sm text-gray-600">Drag & drop a file here or <span class="text-blue-600 underline">browse</span></p>

    <input type="file" class="hidden" x-ref="fileInput" @change="handleFiles($event)">
    
    <template x-if="fileName">
        <p class="mt-4 text-sm text-green-600 font-medium">Selected: <span x-text="fileName"></span></p>
    </template>
</div>


    <div class="mb-4">
        <label for="pesan" class="block text-sm font-medium mb-1">Pesan Tambahan</label>
        <textarea id="pesan" name="pesan" rows="3"
            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
    </div>

    <button
        class="w-full py-2 rounded-lg bg-gradient-to-r from-cyan-400 to-blue-600 text-white font-semibold hover:opacity-90 transition">
        Submit
    </button>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function fileUploadComponent() {
        return {
            fileName: '',
            handleFiles(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                }
            },
            handleDrop(event) {
                const file = event.dataTransfer.files[0];
                if (file) {
                    this.fileName = file.name;
                }
            }
        };
    }
</script>


@endsection