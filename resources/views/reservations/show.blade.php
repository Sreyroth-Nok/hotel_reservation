@extends('layouts.app')

@section('title', 'Reservation Details - Hotel Reservation System')

@section('page-title', 'Reservation Details')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-accent">Dashboard</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center">
        <a href="{{ route('reservations.index') }}" class="text-gray-500 hover:text-accent">Reservations</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        #{{ str_pad($reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}
    </li>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Reservation Info -->
        <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
            <div class="px-6 py-4 border-b border-luxury-border bg-gradient-to-r from-primary to-primary-dark flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-serif font-bold text-white">
                        Reservation #{{ str_pad($reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}
                    </h2>
                    <p class="text-gray-300 text-sm mt-1">Created {{ $reservation->created_at->format('M d, Y') }}</p>
                </div>
                @include('components.status-badge', ['status' => $reservation->status])
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Guest Information -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Guest Information</h3>
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-accent to-accent-light flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                                {{ $reservation->guest ? substr($reservation->guest->full_name, 0, 2) : 'N/A' }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-lg">{{ $reservation->guest->full_name ?? 'Unknown Guest' }}</p>
                                <p class="text-gray-600 text-sm">{{ $reservation->guest->email ?? '' }}</p>
                                <p class="text-gray-600 text-sm">{{ $reservation->guest->phone ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Room Information -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Room Information</h3>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Room Number:</span>
                                <span class="font-semibold text-gray-900">{{ $reservation->room->room_number }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Room Type:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-accent/10 text-accent">
                                    {{ $reservation->room->roomType->type_name }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Price/Night:</span>
                                <span class="font-semibold text-green-600">${{ number_format($reservation->room->roomType->price_per_night, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-luxury-border">

                <!-- Booking Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Check-In</h3>
                        <p class="text-2xl font-serif font-bold text-primary">{{ $reservation->check_in->format('M d') }}</p>
                        <p class="text-sm text-gray-600">{{ $reservation->check_in->format('Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Check-Out</h3>
                        <p class="text-2xl font-serif font-bold text-primary">{{ $reservation->check_out->format('M d') }}</p>
                        <p class="text-sm text-gray-600">{{ $reservation->check_out->format('Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Duration</h3>
                        <p class="text-2xl font-serif font-bold text-primary">{{ $reservation->calculateTotalDays() }}</p>
                        <p class="text-sm text-gray-600">Night(s)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
            <div class="px-6 py-4 border-b border-luxury-border flex items-center justify-between">
                <h2 class="text-xl font-serif font-semibold text-primary">Payment Details</h2>
                @if(!$reservation->isFullyPaid())
                    <a href="{{ route('payments.create', $reservation->reservation_id) }}" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-light transition-colors text-sm font-medium">
                        Add Payment
                    </a>
                @endif
            </div>

            <div class="p-6">
                <!-- Payment Summary -->
                <div class="bg-gradient-to-br from-accent/5 to-accent-light/5 rounded-xl p-6 border border-accent/20 mb-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Total Amount:</span>
                            <span class="font-semibold text-lg">${{ number_format($reservation->total_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Total Paid:</span>
                            <span class="font-semibold text-green-600 text-lg">${{ number_format($reservation->getTotalPaidAmount(), 2) }}</span>
                        </div>
                        <hr class="border-accent/30">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-800">Balance:</span>
                            <span class="font-serif font-bold text-2xl {{ $reservation->getRemainingBalance() > 0 ? 'text-red-600' : 'text-green-600' }}">
                                ${{ number_format($reservation->getRemainingBalance(), 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                @if($reservation->payments->count() > 0)
                <div>
                    <h3 class="font-semibold text-gray-800 mb-4">Payment History</h3>
                    <div class="space-y-3">
                        @foreach($reservation->payments as $payment)
                        <div class="flex items-center justify-between p-4 bg-luxury-bg rounded-lg border border-luxury-border">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</p>
                                    <p class="text-sm text-gray-600">{{ ucfirst($payment->payment_method) }} - {{ $payment->payment_date->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <p class="text-gray-500">No payments recorded yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
            <div class="px-6 py-4 border-b border-luxury-border">
                <h2 class="text-lg font-serif font-semibold text-primary">Quick Actions</h2>
            </div>
            <div class="p-6 space-y-3">
                @if($reservation->status === 'booked')
                    <form action="{{ route('reservations.check-in', $reservation->reservation_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Check In</span>
                        </button>
                    </form>
                @endif

                @if($reservation->status === 'checked_in')
                    <form action="{{ route('reservations.check-out', $reservation->reservation_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Check Out</span>
                        </button>
                    </form>
                @endif

                @if($reservation->status !== 'cancelled' && $reservation->status !== 'checked_out')
                    <a href="{{ route('reservations.edit', $reservation->reservation_id) }}" class="block w-full px-4 py-3 bg-accent text-white rounded-lg hover:bg-accent-light transition-colors font-medium text-center">
                        Edit Reservation
                    </a>
                @endif

                @if($reservation->status !== 'cancelled' && $reservation->status !== 'checked_out')
                    <form action="{{ route('reservations.cancel', $reservation->reservation_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            Cancel Reservation
                        </button>
                    </form>
                @endif

                <a href="{{ route('reservations.index') }}" class="block w-full px-4 py-3 border-2 border-luxury-border text-gray-700 rounded-lg hover:bg-luxury-bg transition-colors font-medium text-center">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
            <div class="px-6 py-4 border-b border-luxury-border">
                <h2 class="text-lg font-serif font-semibold text-primary">Activity Timeline</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Reservation Created</p>
                            <p class="text-xs text-gray-500">{{ $reservation->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    @if($reservation->status === 'checked_in' || $reservation->status === 'checked_out')
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Checked In</p>
                            <p class="text-xs text-gray-500">{{ $reservation->check_in->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($reservation->status === 'checked_out')
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Checked Out</p>
                            <p class="text-xs text-gray-500">{{ $reservation->check_out->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection