@extends('layouts.app')

@section('title', 'Guest Details - ' . $guest->full_name)

@section('page-title', 'Guest Details')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-accent">Dashboard</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center">
        <a href="{{ route('guests.index') }}" class="text-gray-500 hover:text-accent">Guests</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        {{ $guest->full_name }}
    </li>
@endsection

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-serif font-bold text-primary mb-2">Guest Details</h1>
        <p class="text-gray-600">View and manage guest information</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('guests.edit', $guest->guest_id) }}" class="px-4 py-2 bg-warning text-white rounded-lg hover:bg-warning-light transition-colors flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span>Edit</span>
        </a>
        <a href="{{ route('guests.index') }}" class="px-4 py-2 border border-luxury-border rounded-lg hover:bg-luxury-bg transition-colors flex items-center space-x-2">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">Back to List</span>
        </a>
    </div>
</div>

<div class="row">
    <!-- Guest Information Card -->
    <div class="col-lg-4 mb-4">
        <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden h-full">
            <div class="bg-gradient-to-r from-accent to-accent-light p-6 text-white">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                        {{ substr($guest->full_name, 0, 2) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">{{ $guest->full_name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20">
                            Guest
                        </span>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="text-sm font-medium text-gray-900">{{ $guest->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Phone</p>
                            <p class="text-sm font-medium text-gray-900">{{ $guest->phone }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">ID Card</p>
                            <p class="text-sm font-medium text-gray-900">{{ $guest->id_card_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Address</p>
                            <p class="text-sm font-medium text-gray-900">{{ $guest->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Registered</p>
                            <p class="text-sm font-medium text-gray-900">{{ $guest->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservation History -->
    <div class="col-lg-8 mb-4">
        <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden h-full">
            <div class="px-6 py-4 border-b border-luxury-border">
                <h3 class="text-lg font-semibold text-gray-900">Reservation History</h3>
            </div>
            <div class="p-6">
                @if($reservations->count() > 0)
                    <div class="table-responsive">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="pb-3">Reservation</th>
                                    <th class="pb-3">Room</th>
                                    <th class="pb-3">Dates</th>
                                    <th class="pb-3">Status</th>
                                    <th class="pb-3">Total</th>
                                    <th class="pb-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-luxury-border">
                                @foreach($reservations as $reservation)
                                <tr class="hover:bg-luxury-bg/50 transition-colors">
                                    <td class="py-3">
                                        <span class="text-sm font-medium text-gray-900">#{{ str_pad($reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-sm text-gray-900">{{ $reservation->room->room_number }}</span>
                                        <span class="text-xs text-gray-500 block">{{ $reservation->room->roomType->type_name }}</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-sm text-gray-600">{{ $reservation->check_in->format('M d') }} - {{ $reservation->check_out->format('M d, Y') }}</span>
                                        <span class="text-xs text-gray-500 block">{{ $reservation->calculateTotalDays() }} nights</span>
                                    </td>
                                    <td class="py-3">
                                        @switch($reservation->status)
                                            @case('booked')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">Booked</span>
                                                @break
                                            @case('checked_in')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">Checked In</span>
                                                @break
                                            @case('checked_out')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray/10 text-gray">Checked Out</span>
                                                @break
                                            @case('cancelled')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger/10 text-danger">Cancelled</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="py-3">
                                        <span class="text-sm font-semibold text-green-600">${{ number_format($reservation->total_price, 2) }}</span>
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('reservations.show', $reservation->reservation_id) }}" class="text-accent hover:text-accent-light transition-colors font-medium text-sm">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($reservations->hasPages())
                    <div class="mt-4">
                        {{ $reservations->links() }}
                    </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500">No reservation history found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
