@extends('layouts.app')

@section('title', 'Dashboard - Hotel Reservation System')

@section('page-title', 'Dashboard Overview')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <span class="text-accent">Dashboard</span>
    </li>
@endsection

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Rooms -->
    <div class="bg-white rounded-2xl border border-luxury-border p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Rooms</p>
                <h3 class="text-4xl font-serif font-bold text-primary">{{ $stats['total_rooms'] ?? 45 }}</h3>
            </div>
            <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
        <div class="flex items-center text-sm pt-4 border-t border-luxury-border">
            <span class="text-green-600 font-semibold">↗ 12%</span>
            <span class="text-gray-500 ml-2">vs last month</span>
        </div>
    </div>

    <!-- Active Bookings -->
    <div class="bg-white rounded-2xl border border-luxury-border p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Active Bookings</p>
                <h3 class="text-4xl font-serif font-bold text-primary">{{ $stats['active_bookings'] ?? 28 }}</h3>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
        <div class="flex items-center text-sm pt-4 border-t border-luxury-border">
            <span class="text-green-600 font-semibold">↗ 8%</span>
            <span class="text-gray-500 ml-2">vs last month</span>
        </div>
    </div>

    <!-- Monthly Revenue -->
    <div class="bg-white rounded-2xl border border-luxury-border p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Monthly Revenue</p>
                <h3 class="text-4xl font-serif font-bold text-primary">${{ number_format($stats['monthly_revenue'] ?? 12450, 0) }}</h3>
            </div>
            <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="flex items-center text-sm pt-4 border-t border-luxury-border">
            <span class="text-green-600 font-semibold">↗ 23%</span>
            <span class="text-gray-500 ml-2">vs last month</span>
        </div>
    </div>

    <!-- Occupancy Rate -->
    <div class="bg-white rounded-2xl border border-luxury-border p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Occupancy Rate</p>
                <h3 class="text-4xl font-serif font-bold text-primary">{{ $stats['occupancy_rate'] ?? 92 }}%</h3>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
        <div class="flex items-center text-sm pt-4 border-t border-luxury-border">
            <span class="text-red-600 font-semibold">↘ 3%</span>
            <span class="text-gray-500 ml-2">vs last month</span>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('reservations.create') }}" class="group bg-white border-2 border-dashed border-luxury-border rounded-xl p-6 hover:border-accent hover:shadow-lg transition-all duration-300 text-center">
        <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-accent/20 transition-colors">
            <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">New Reservation</h3>
        <p class="text-sm text-gray-500">Create a new booking</p>
    </a>

    <a href="#" class="group bg-white border-2 border-dashed border-luxury-border rounded-xl p-6 hover:border-accent hover:shadow-lg transition-all duration-300 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">Check-In</h3>
        <p class="text-sm text-gray-500">Process guest arrival</p>
    </a>

    <a href="#" class="group bg-white border-2 border-dashed border-luxury-border rounded-xl p-6 hover:border-accent hover:shadow-lg transition-all duration-300 text-center">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">Check-Out</h3>
        <p class="text-sm text-gray-500">Process guest departure</p>
    </a>

    <a href="{{ route('rooms.index') }}" class="group bg-white border-2 border-dashed border-luxury-border rounded-xl p-6 hover:border-accent hover:shadow-lg transition-all duration-300 text-center">
        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-200 transition-colors">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">Maintenance</h3>
        <p class="text-sm text-gray-500">Report room issue</p>
    </a>
</div>

<!-- Recent Reservations -->
<div class="bg-white rounded-2xl border border-luxury-border overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-luxury-border flex items-center justify-between">
        <h2 class="text-2xl font-serif font-semibold text-primary">Recent Reservations</h2>
        <a href="{{ route('reservations.index') }}" class="px-4 py-2 bg-gradient-to-r from-accent to-accent-light text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-medium">
            View All
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-luxury-bg">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Guest Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Room</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Check-In</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Check-Out</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-luxury-border">
                @forelse($recent_reservations ?? [] as $reservation)
                <tr class="hover:bg-luxury-bg/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #{{ str_pad($reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $reservation->user->full_name }}</div>
                        <div class="text-sm text-gray-500">{{ $reservation->user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $reservation->room->room_number }}</div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent/10 text-accent">
                            {{ $reservation->room->roomType->type_name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $reservation->check_in->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $reservation->check_out->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @include('components.status-badge', ['status' => $reservation->status])
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-serif font-semibold text-green-600">
                            ${{ number_format($reservation->total_price, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('reservations.show', $reservation->reservation_id) }}" class="text-accent hover:text-accent-light mr-3">View</a>
                        <a href="{{ route('reservations.edit', $reservation->reservation_id) }}" class="text-primary hover:text-primary-dark">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">No recent reservations found</p>
                        <a href="{{ route('reservations.create') }}" class="inline-block mt-4 text-accent hover:text-accent-light font-medium">Create your first reservation</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Room Availability -->
<div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
    <div class="px-6 py-4 border-b border-luxury-border flex items-center justify-between">
        <h2 class="text-2xl font-serif font-semibold text-primary">Room Availability</h2>
        <a href="{{ route('rooms.index') }}" class="px-4 py-2 border-2 border-accent text-accent rounded-lg hover:bg-accent hover:text-white transition-all duration-300 text-sm font-medium">
            Manage Rooms
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-luxury-bg">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Room Number</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price/Night</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-luxury-border">
                @forelse($available_rooms ?? [] as $room)
                <tr class="hover:bg-luxury-bg/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $room->room_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-accent/10 text-accent">
                            {{ $room->roomType->type_name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-serif font-semibold text-green-600">
                            ${{ number_format($room->roomType->price_per_night, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @include('components.room-status-badge', ['status' => $room->status])
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if($room->status === 'available')
                            <a href="{{ route('reservations.create', ['room_id' => $room->room_id]) }}" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-light transition-colors">
                                Book Now
                            </a>
                        @else
                            <button disabled class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                                Unavailable
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">No rooms found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection