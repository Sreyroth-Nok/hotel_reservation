@extends('layouts.app')

@section('title', 'Add New Guest')

@section('page-title', 'Add New Guest')

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
        Add New
    </li>
@endsection

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-serif font-bold text-primary mb-2">Add New Guest</h1>
        <p class="text-gray-600">Enter the guest's information below</p>
    </div>
    <a href="{{ route('guests.index') }}" class="px-4 py-2 border border-luxury-border rounded-lg hover:bg-luxury-bg transition-colors flex items-center space-x-2">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        <span class="font-medium">Back to List</span>
    </a>
</div>

<div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
    <div class="p-8">
        <form action="{{ route('guests.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="md:col-span-2">
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent @error('full_name') border-danger @enderror" 
                           id="full_name" name="full_name" value="{{ old('full_name') }}" placeholder="Enter full name" required>
                    @error('full_name')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-danger">*</span></label>
                    <input type="email" class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent @error('email') border-danger @enderror" 
                           id="email" name="email" value="{{ old('email') }}" placeholder="guest@email.com" required>
                    @error('email')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-danger">*</span></label>
                    <input type="text" class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent @error('phone') border-danger @enderror" 
                           id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 234 567 8900" required>
                    @error('phone')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ID Card Number -->
                <div>
                    <label for="id_card_number" class="block text-sm font-medium text-gray-700 mb-1">ID Card Number</label>
                    <input type="text" class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent @error('id_card_number') border-danger @enderror" 
                           id="id_card_number" name="id_card_number" value="{{ old('id_card_number') }}" placeholder="ID/Passport number">
                    @error('id_card_number')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent @error('address') border-danger @enderror" 
                           id="address" name="address" value="{{ old('address') }}" placeholder="Full address">
                    @error('address')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('guests.index') }}" class="px-6 py-3 border border-luxury-border rounded-lg text-gray-700 hover:bg-luxury-bg transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-accent to-accent-light text-white rounded-lg hover:shadow-lg transition-all duration-300 font-medium flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Save Guest</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
