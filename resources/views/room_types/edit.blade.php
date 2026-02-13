@extends('layouts.app')

@section('title', 'Edit Room Type - Hotel Reservation System')

@section('page-title', 'Edit Room Type')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-accent hover:text-accent-light">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('room-types.index') }}" class="text-accent hover:text-accent-light">Room Types</a>
        <span class="mx-2">/</span>
        <span class="text-gray-500">Edit</span>
    </li>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-serif font-bold text-primary">Edit Room Type</h1>
    <p class="text-gray-500 mt-1">Update the room type details</p>
</div>

<div class="bg-white rounded-2xl border border-luxury-border p-8">
    <form action="{{ route('room-types.update', $roomType->type_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Type Name -->
            <div>
                <label for="type_name" class="block text-sm font-medium text-gray-700 mb-2">Type Name *</label>
                <input type="text" name="type_name" id="type_name" value="{{ old('type_name', $roomType->type_name) }}" 
                    class="w-full px-4 py-3 border border-luxury-border rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-colors @error('type_name') border-red-500 @enderror"
                    placeholder="e.g., Deluxe Suite, Standard Room">
                @error('type_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price per Night -->
            <div>
                <label for="price_per_night" class="block text-sm font-medium text-gray-700 mb-2">Price per Night ($) *</label>
                <input type="number" name="price_per_night" id="price_per_night" value="{{ old('price_per_night', $roomType->price_per_night) }}" min="0" step="0.01"
                    class="w-full px-4 py-3 border border-luxury-border rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-colors @error('price_per_night') border-red-500 @enderror"
                    placeholder="0.00">
                @error('price_per_night')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Capacity -->
            <div>
                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Capacity (Number of Guests) *</label>
                <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $roomType->capacity) }}" min="1"
                    class="w-full px-4 py-3 border border-luxury-border rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-colors @error('capacity') border-red-500 @enderror"
                    placeholder="2">
                @error('capacity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amenities -->
            <div>
                <label for="amenities" class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                <input type="text" name="amenities" id="amenities" value="{{ old('amenities', $roomType->amenities) }}"
                    class="w-full px-4 py-3 border border-luxury-border rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-colors @error('amenities') border-red-500 @enderror"
                    placeholder="e.g., WiFi, TV, Air Conditioning">
            </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="4"
                class="w-full px-4 py-3 border border-luxury-border rounded-xl focus:ring-2 focus:ring-accent focus:border-accent transition-colors @error('description') border-red-500 @enderror"
                placeholder="Describe the room type...">{{ old('description', $roomType->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('room-types.index') }}" class="px-6 py-3 border border-luxury-border text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-accent text-white rounded-xl hover:bg-accent-light transition-colors shadow-lg">
                Update Room Type
            </button>
        </div>
    </form>
</div>
