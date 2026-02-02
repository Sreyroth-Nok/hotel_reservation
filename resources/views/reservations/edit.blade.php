@extends('layouts.app')

@section('title', 'Edit Reservation - Hotel Reservation System')

@section('page-title', 'Edit Reservation')

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
        Edit #{{ str_pad($reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden shadow-lg">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-luxury-border bg-gradient-to-r from-primary to-primary-dark">
            <h2 class="text-2xl font-serif font-bold text-white">Edit Reservation</h2>
            <p class="text-gray-300 mt-1">Update reservation details</p>
        </div>

        <form action="{{ route('reservations.update', $reservation->reservation_id) }}" method="POST" class="p-8" x-data="reservationForm()">
            @csrf
            @method('PUT')

            <!-- Guest Selection -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Guest Information
                </h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Guest *</label>
                        <div class="flex gap-2">
                            <select name="guest_id" required 
                                    x-model="selectedGuest"
                                    class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                                <option value="">Choose a guest...</option>
                                @foreach($guests ?? [] as $guest)
                                    <option value="{{ $guest->guest_id }}" {{ $reservation->guest_id == $guest->guest_id ? 'selected' : '' }}>
                                        {{ $guest->full_name }} ({{ $guest->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('guest_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Dates Selection -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Booking Dates
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-In Date *</label>
                        <input type="date" 
                               name="check_in" 
                               x-model="checkIn"
                               @change="calculateNights()"
                               value="{{ old('check_in', $reservation->check_in->format('Y-m-d')) }}"
                               required 
                               class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                        @error('check_in')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-Out Date *</label>
                        <input type="date" 
                               name="check_out" 
                               x-model="checkOut"
                               @change="calculateNights()"
                               value="{{ old('check_out', $reservation->check_out->format('Y-m-d')) }}"
                               :min="checkIn"
                               required 
                               class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                        @error('check_out')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div x-show="nights > 0" class="mt-4 p-4 bg-accent/10 rounded-lg border border-accent/20">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold">Total Nights:</span> 
                        <span x-text="nights" class="text-accent font-bold"></span>
                    </p>
                </div>
            </div>

            <!-- Room Selection -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Room Selection
                </h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Room *</label>
                        <select name="room_id" 
                                x-model="selectedRoom"
                                @change="updatePrice()"
                                required 
                                class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                            <option value="">Choose a room...</option>
                            @foreach($available_rooms ?? [] as $room)
                                <option value="{{ $room->room_id }}" 
                                        data-price="{{ $room->roomType->price_per_night }}"
                                        {{ $reservation->room_id == $room->room_id ? 'selected' : '' }}>
                                    {{ $room->room_number }} - {{ $room->roomType->type_name }} (${{ number_format($room->roomType->price_per_night, 2) }}/night)
                                </option>
                            @endforeach
                        </select>
                        @error('room_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Price Summary -->
            <div x-show="pricePerNight > 0 && nights > 0" class="mb-8 p-6 bg-gradient-to-br from-accent/5 to-accent-light/5 rounded-xl border-2 border-accent/20">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Price Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Price per night:</span>
                        <span class="font-semibold text-gray-800">$<span x-text="pricePerNight.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Number of nights:</span>
                        <span class="font-semibold text-gray-800" x-text="nights"></span>
                    </div>
                    <hr class="border-accent/20">
                    <div class="flex justify-between items-center text-lg">
                        <span class="font-bold text-gray-800">Total Amount:</span>
                        <span class="font-serif font-bold text-2xl text-accent">$<span x-text="totalPrice.toFixed(2)"></span></span>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Status
                </h3>
                <div>
                    <select name="status" class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                        <option value="booked" {{ $reservation->status == 'booked' ? 'selected' : '' }}>Booked</option>
                        <option value="checked_in" {{ $reservation->status == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                        <option value="checked_out" {{ $reservation->status == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                        <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-luxury-border">
                <a href="{{ route('reservations.index') }}" class="px-6 py-3 border-2 border-luxury-border text-gray-700 rounded-lg hover:bg-luxury-bg transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-accent to-accent-light text-white rounded-lg hover:shadow-lg transition-all duration-300 font-medium flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Update Reservation</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function reservationForm() {
        return {
            checkIn: '{{ $reservation->check_in->format('Y-m-d') }}',
            checkOut: '{{ $reservation->check_out->format('Y-m-d') }}',
            selectedRoom: '{{ $reservation->room_id }}',
            selectedGuest: '{{ $reservation->guest_id ?? '' }}',
            nights: {{ $reservation->calculateTotalDays() }},
            pricePerNight: {{ $reservation->room->roomType->price_per_night }},
            totalPrice: {{ $reservation->total_price }},

            calculateNights() {
                if (this.checkIn && this.checkOut) {
                    const start = new Date(this.checkIn);
                    const end = new Date(this.checkOut);
                    const diffTime = Math.abs(end - start);
                    this.nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    this.updatePrice();
                }
            },

            updatePrice() {
                if (this.selectedRoom) {
                    const selectedOption = document.querySelector(`option[value="${this.selectedRoom}"]`);
                    if (selectedOption && selectedOption.dataset.price) {
                        this.pricePerNight = parseFloat(selectedOption.dataset.price) || 0;
                        this.totalPrice = this.pricePerNight * this.nights;
                    }
                }
            }
        }
    }
</script>
@endpush
@endsection
