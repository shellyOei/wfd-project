@extends('admin.layout')

@section('title', 'Generate Practice Schedules')
@section('page-title', 'Generate Practice Schedules')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 max-w-2xl mx-auto">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Generate Practice Schedule Slots</h3>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.practice-schedules.generate.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor <span class="text-red-500">*</span></label>
                    <select name="doctor_id" id="doctor_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->front_title }} {{ $doctor->name }} {{ $doctor->back_title }} ({{ $doctor->specialization->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Generate From Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('start_date') }}" required>
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Generate To Date <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('end_date') }}" required>
                </div>
            </div>

            <div class="mt-8">
                <p class="text-sm text-gray-600 mb-4">This will generate specific practice schedule slots for the selected doctor based on their defined "Day Availabilities" within the date range. Existing slots will not be duplicated.</p>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.practice-schedules.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-play mr-2"></i> Generate Schedules
                </button>
            </div>
        </form>
    </div>
@endsection