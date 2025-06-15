@extends('admin.layout')

@section('title', 'Edit Doctor Availability')
@section('page-title', 'Edit Doctor Availability')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 max-w-2xl mx-auto">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Edit Availability for {{ $dayAvailable->doctor->name }} on {{ $dayAvailable->day }}</h3>

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

        <form action="{{ route('admin.day-availables.update', $dayAvailable->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor <span class="text-red-500">*</span></label>
                    <select name="doctor_id" id="doctor_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ (old('doctor_id', $dayAvailable->doctor_id) == $doctor->id) ? 'selected' : '' }}>
                                {{ $doctor->front_title }} {{ $doctor->name }} {{ $doctor->back_title }} ({{ $doctor->specialization->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="day" class="block text-sm font-medium text-gray-700">Day of Week <span class="text-red-500">*</span></label>
                    <select name="day" id="day" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                        @foreach($daysOfWeek as $dayOption)
                            <option value="{{ $dayOption }}" {{ (old('day', $dayAvailable->day) == $dayOption) ? 'selected' : '' }}>
                                {{ $dayOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" id="start_time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('start_time', \Carbon\Carbon::parse($dayAvailable->start_time)->format('H:i')) }}" required>
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700">End Time <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" id="end_time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('end_time', \Carbon\Carbon::parse($dayAvailable->end_time)->format('H:i')) }}" required>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.day-availables.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Availability
                </button>
            </div>
        </form>
    </div>
@endsection