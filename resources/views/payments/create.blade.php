@extends('layouts.app')

@section('title', 'Create Payment - Hotel Reservation System')

@section('page-title', 'Create Payment')

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
    <li class="inline-flex items-center">
        <a href="{{ route('reservations.show', $reservation->reservation_id) }}" class="text-gray-500 hover:text-accent">Reservation #{{ str_pad($reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        Create Payment
    </li>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden shadow-lg">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-luxury-border bg-gradient-to-r from-primary to-primary-dark">
            <h2 class="text-2xl font-serif font-bold text-white">Process Payment</h2>
            <p class="text-gray-300 mt-1">Reservation #{{ str_pad($reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Reservation Summary -->
        <div class="p-8 bg-luxury-bg/50 border-b border-luxury-border">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Reservation Summary</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Guest</p>
                    <p class="font-medium text-gray-900">{{ $reservation->guest->full_name ?? 'Unknown Guest' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Room</p>
                    <p class="font-medium text-gray-900">{{ $reservation->room->room_number }} - {{ $reservation->room->roomType->type_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Check-In</p>
                    <p class="font-medium text-gray-900">{{ $reservation->check_in->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Check-Out</p>
                    <p class="font-medium text-gray-900">{{ $reservation->check_out->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="p-8">
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <p class="text-sm text-gray-500">Total Amount</p>
                    <p class="text-2xl font-serif font-bold text-gray-900">${{ number_format($reservation->total_price, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-xl">
                    <p class="text-sm text-gray-500">Already Paid</p>
                    <p class="text-2xl font-serif font-bold text-green-600">${{ number_format($reservation->getTotalPaidAmount(), 2) }}</p>
                </div>
                <div class="text-center p-4 bg-amber-50 rounded-xl">
                    <p class="text-sm text-gray-500">Remaining</p>
                    <p class="text-2xl font-serif font-bold text-amber-600">${{ number_format($reservation->getRemainingBalance(), 2) }}</p>
                </div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf

                <input type="hidden" name="reservation_id" value="{{ $reservation->reservation_id }}">

                <!-- Amount -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Amount *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-500">$</span>
                        <input type="number" 
                               name="amount" 
                               value="{{ old('amount', $reservation->getRemainingBalance()) }}"
                               min="0.01"
                               max="{{ $reservation->getRemainingBalance() }}"
                               step="0.01"
                               required
                               class="w-full pl-8 pr-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="cash" class="sr-only peer" {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-luxury-border rounded-xl text-center hover:border-accent peer-checked:border-accent peer-checked:bg-accent/5 transition-all">
                                <div class="text-3xl mb-2">💵</div>
                                <p class="font-medium text-gray-800">Cash</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="card" class="sr-only peer" {{ old('payment_method') == 'card' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-luxury-border rounded-xl text-center hover:border-accent peer-checked:border-accent peer-checked:bg-accent/5 transition-all">
                                <div class="text-3xl mb-2">💳</div>
                                <p class="font-medium text-gray-800">Card</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="online" class="sr-only peer" {{ old('payment_method') == 'online' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-luxury-border rounded-xl text-center hover:border-accent peer-checked:border-accent peer-checked:bg-accent/5 transition-all">
                                <div class="text-3xl mb-2">🌐</div>
                                <p class="font-medium text-gray-800">Online</p>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quick Amount Buttons -->
                <div class="mb-6">
                    <p class="text-sm text-gray-500 mb-2">Quick Amount</p>
                    <div class="flex space-x-2">
                        <button type="button" onclick="setAmount({{ $reservation->getRemainingBalance() }})" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-light transition-colors text-sm">
                            Full Amount
                        </button>
                        <button type="button" onclick="setAmount({{ $reservation->getRemainingBalance() / 2 }})" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                            Half
                        </button>
                        <button type="button" onclick="setAmount(100)" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                            $100
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-luxury-border">
                    <a href="{{ route('reservations.show', $reservation->reservation_id) }}" class="px-6 py-3 border-2 border-luxury-border text-gray-700 rounded-lg hover:bg-luxury-bg transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-accent to-accent-light text-white rounded-lg hover:shadow-lg transition-all duration-300 font-medium flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Process Payment</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function setAmount(amount) {
        document.querySelector('input[name="amount"]').value = amount.toFixed(2);
    }
</script>
@endpush
@endsection
