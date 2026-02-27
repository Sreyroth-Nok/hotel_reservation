@extends('layouts.app')

@section('title', 'Rooms - Hotel Reservation System')

@section('page-title', 'Rooms Management')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-accent">Dashboard</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        Rooms
    </li>
@endsection

@section('content')
<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
    <div class="flex-1">
        <h1 class="text-3xl font-serif font-bold text-primary mb-2">Room Inventory</h1>
        <p class="text-gray-600">Manage all rooms and their availability</p>
    </div>
    
    <div class="flex items-center space-x-3">
        <a href="{{ route('rooms.create') }}" class="px-6 py-2 bg-gradient-to-r from-accent to-accent-light text-white rounded-lg hover:shadow-lg transition-all duration-300 font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Add New Room</span>
        </a>
        
        <!-- Export Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="px-4 py-2 border border-luxury-border rounded-lg hover:bg-luxury-bg transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span class="font-medium">Export</span>
            </button>
            
            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-luxury-border py-2 z-10">
                <a href="{{ route('export.rooms.csv', request()->query()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-luxury-bg flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Export as CSV</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search Form -->
<div class="mb-6 bg-white rounded-xl border border-luxury-border p-4">
    <form action="{{ route('rooms.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ $query ?? '' }}" 
                       placeholder="Search by room number..."
                       class="w-full px-4 py-2 pl-10 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <div class="flex gap-2">
            <select name="status" class="px-4 py-2 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                <option value="">All Status</option>
                <option value="available" {{ ($status ?? '') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="occupied" {{ ($status ?? '') === 'occupied' ? 'selected' : '' }}>Occupied</option>
                <option value="maintenance" {{ ($status ?? '') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            <select name="type_id" class="px-4 py-2 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                <option value="">All Types</option>
                @foreach($room_types ?? [] as $type)
                <option value="{{ $type->type_id }}" {{ ($type_id ?? '') == $type->type_id ? 'selected' : '' }}>
                    {{ $type->type_name }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                Search
            </button>
            @if($query || $status || $type_id)
            <a href="{{ route('rooms.index') }}" class="px-6 py-2 border border-luxury-border text-gray-700 rounded-lg hover:bg-luxury-bg transition-colors font-medium">
                Clear
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-luxury-border p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Rooms</p>
                <p class="text-3xl font-serif font-bold text-primary mt-1">{{ $rooms->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-luxury-border p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Available</p>
                <p class="text-3xl font-serif font-bold text-green-600 mt-1">
                    {{ $rooms->where('status', 'available')->count() }}
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-luxury-border p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Occupied</p>
                <p class="text-3xl font-serif font-bold text-amber-600 mt-1">
                    {{ $rooms->where('status', 'occupied')->count() }}
                </p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-luxury-border p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Maintenance</p>
                <p class="text-3xl font-serif font-bold text-red-600 mt-1">
                    {{ $rooms->where('status', 'maintenance')->count() }}
                </p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Rooms Grid View -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($rooms as $room)
    <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden hover:shadow-lg transition-all duration-300">
        <!-- Room Header -->
        <div class="bg-gradient-to-br from-primary to-primary-dark p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-2xl font-serif font-bold">{{ $room->room_number }}</h3>
                @include('components.room-status-badge', ['status' => $room->status])
            </div>
            <p class="text-gray-300 text-sm">{{ $room->roomType->type_name }}</p>
        </div>

        <!-- Room Body -->
        <div class="p-6">
            <div class="space-y-3 mb-6">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Price per night:</span>
                    <span class="font-serif font-bold text-lg text-green-600">
                        ${{ number_format($room->roomType->price_per_night, 2) }}
                    </span>
                </div>
                
                @if($room->roomType->description)
                <p class="text-sm text-gray-600 line-clamp-2">{{ $room->roomType->description }}</p>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('rooms.show', $room->room_id) }}" 
                   class="flex-1 px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-light transition-colors text-sm font-medium text-center">
                    View Details
                </a>
                @if($room->status === 'available')
                <a href="{{ route('reservations.create', ['room_id' => $room->room_id]) }}" 
                   class="px-4 py-2 border-2 border-accent text-accent rounded-lg hover:bg-accent hover:text-white transition-colors text-sm font-medium">
                    Book
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="bg-white rounded-2xl border border-luxury-border p-16 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <p class="text-gray-500 text-xl font-medium mb-2">No rooms found</p>
            <p class="text-gray-400 mb-6">Add your first room to get started</p>
            <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-6 py-3 bg-accent text-white rounded-lg hover:bg-accent-light transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Room
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($rooms->hasPages())
<div class="mt-8">
    {{ $rooms->links() }}
</div>
@endif
@endsection