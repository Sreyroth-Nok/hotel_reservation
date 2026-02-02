@extends('layouts.app')

@section('title', 'Reservations - Hotel Reservation System')

@section('page-title', 'Reservations')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-accent">Dashboard</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        Reservations
    </li>
@endsection

@section('content')
<!-- Header with Filter and Actions -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
    <div class="flex-1">
        <h1 class="text-3xl font-serif font-bold text-primary mb-2">All Reservations</h1>
        <p class="text-gray-600">Manage your hotel bookings and reservations</p>
    </div>
    
    <div class="flex items-center space-x-3">
        <!-- Filter Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="px-4 py-2 border border-luxury-border rounded-lg hover:bg-luxury-bg transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span class="font-medium">Filter</span>
            </button>
            
            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-luxury-border py-2 z-10">
                <a href="{{ route('reservations.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-luxury-bg">All Reservations</a>
                <a href="{{ route('reservations.index', ['status' => 'booked']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-luxury-bg">Booked</a>
                <a href="{{ route('reservations.index', ['status' => 'checked_in']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-luxury-bg">Checked In</a>
                <a href="{{ route('reservations.index', ['status' => 'checked_out']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-luxury-bg">Checked Out</a>
                <a href="{{ route('reservations.index', ['status' => 'cancelled']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-luxury-bg">Cancelled</a>
            </div>
        </div>

        <!-- Create Button - Links to separate page -->
        <a href="{{ route('reservations.create') }}" class="px-6 py-2 bg-gradient-to-r from-accent to-accent-light text-white rounded-lg hover:shadow-lg transition-all duration-300 font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>New Reservation</span>
        </a>
    </div>
</div>

<!-- Reservations Table -->
<div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-luxury-bg">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Guest</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Room</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Check-In</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Check-Out</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nights</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-luxury-border">
                @forelse($reservations as $reservation)
                <tr class="hover:bg-luxury-bg/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">#{{ str_pad($reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accent to-accent-light flex items-center justify-center text-white font-semibold mr-3">
                                {{ $reservation->guest ? substr($reservation->guest->full_name, 0, 2) : 'N/A' }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $reservation->guest->full_name ?? 'Unknown Guest' }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->guest->email ?? '' }}</div>
                            </div>
                        </div>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $reservation->calculateTotalDays() }} nights
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @include('components.status-badge', ['status' => $reservation->status])
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-serif font-semibold text-green-600">${{ number_format($reservation->total_price, 2) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('reservations.show', $reservation->reservation_id) }}" class="text-primary hover:text-accent transition-colors font-medium">
                                View
                            </a>
                            @if($reservation->status !== 'cancelled')
                            <a href="{{ route('reservations.edit', $reservation->reservation_id) }}" class="text-accent hover:text-accent-light transition-colors font-medium">
                                Edit
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-16 text-center">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-xl font-medium mb-2">No reservations found</p>
                        <p class="text-gray-400 mb-6">Start by creating your first reservation</p>
                        <a href="{{ route('reservations.create') }}" class="inline-flex items-center px-6 py-3 bg-accent text-white rounded-lg hover:bg-accent-light transition-colors font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create New Reservation
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($reservations->hasPages())
    <div class="px-6 py-4 border-t border-luxury-border">
        {{ $reservations->links() }}
    </div>
    @endif
</div>
@endsection
