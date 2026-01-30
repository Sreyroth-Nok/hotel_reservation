@extends('layouts.app')

@section('title', 'Add New Room - Hotel Reservation System')

@section('page-title', 'Add New Room')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-accent">Dashboard</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center">
        <a href="{{ route('rooms.index') }}" class="text-gray-500 hover:text-accent">Rooms</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        Add New
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden shadow-lg">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-luxury-border bg-gradient-to-r from-primary to-primary-dark">
            <h2 class="text-2xl font-serif font-bold text-white">Add New Room</h2>
            <p class="text-gray-300 mt-1">Create a new room in your hotel inventory</p>
        </div>

        <form action="{{ route('rooms.store') }}" method="POST" class="p-8">
            @csrf

            <!-- Room Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Room Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Number *</label>
                        <input type="text" 
                               name="room_number" 
                               value="{{ old('room_number') }}"
                               placeholder="e.g., 101, 205, Suite A"
                               required 
                               class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors @error('room_number') border-red-500 @enderror">
                        @error('room_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Type *</label>
                        <select name="type_id" 
                                required 
                                class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors @error('type_id') border-red-500 @enderror">
                            <option value="">Select room type...</option>
                            @foreach($room_types ?? [] as $type)
                                <option value="{{ $type->type_id }}" {{ old('type_id') == $type->type_id ? 'selected' : '' }}>
                                    {{ $type->type_name }} - ${{ number_format($type->price_per_night, 2) }}/night
                                </option>
                            @endforeach
                        </select>
                        @error('type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Room Status -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Room Status
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative flex cursor-pointer rounded-lg border border-luxury-border p-5 hover:border-accent transition-colors">
                        <input type="radio" name="status" value="available" {{ old('status', 'available') === 'available' ? 'checked' : '' }} class="sr-only peer" required>
                        <div class="flex items-start space-x-3 w-full">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 peer-checked:bg-green-600 transition-colors">
                                <svg class="w-5 h-5 text-green-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-sm">Available</h4>
                                <p class="text-xs text-gray-600">Ready for booking</p>
                            </div>
                        </div>
                        <div class="absolute top-3 right-3 w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-600 peer-checked:bg-green-600 flex items-center justify-center">
                            <div class="w-1.5 h-1.5 bg-white rounded-full hidden peer-checked:block"></div>
                        </div>
                    </label>

                    <label class="relative flex cursor-pointer rounded-lg border border-luxury-border p-5 hover:border-accent transition-colors">
                        <input type="radio" name="status" value="occupied" {{ old('status') === 'occupied' ? 'checked' : '' }} class="sr-only peer">
                        <div class="flex items-start space-x-3 w-full">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0 peer-checked:bg-amber-600 transition-colors">
                                <svg class="w-5 h-5 text-amber-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-sm">Occupied</h4>
                                <p class="text-xs text-gray-600">Currently in use</p>
                            </div>
                        </div>
                        <div class="absolute top-3 right-3 w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-amber-600 peer-checked:bg-amber-600 flex items-center justify-center">
                            <div class="w-1.5 h-1.5 bg-white rounded-full hidden peer-checked:block"></div>
                        </div>
                    </label>

                    <label class="relative flex cursor-pointer rounded-lg border border-luxury-border p-5 hover:border-accent transition-colors">
                        <input type="radio" name="status" value="maintenance" {{ old('status') === 'maintenance' ? 'checked' : '' }} class="sr-only peer">
                        <div class="flex items-start space-x-3 w-full">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 peer-checked:bg-red-600 transition-colors">
                                <svg class="w-5 h-5 text-red-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-sm">Maintenance</h4>
                                <p class="text-xs text-gray-600">Under repair</p>
                            </div>
                        </div>
                        <div class="absolute top-3 right-3 w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-red-600 peer-checked:bg-red-600 flex items-center justify-center">
                            <div class="w-1.5 h-1.5 bg-white rounded-full hidden peer-checked:block"></div>
                        </div>
                    </label>
                </div>
                @error('status')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-luxury-border">
                <a href="{{ route('rooms.index') }}" class="px-6 py-3 border-2 border-luxury-border text-gray-700 rounded-lg hover:bg-luxury-bg transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-accent to-accent-light text-white rounded-lg hover:shadow-lg transition-all duration-300 font-medium flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Add Room</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection