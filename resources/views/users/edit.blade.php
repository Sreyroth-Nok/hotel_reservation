@extends('layouts.app')

@section('title', 'Edit User - Hotel Reservation System')

@section('page-title', 'Edit User')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-accent">Dashboard</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center">
        <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-accent">Users</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        Edit
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden shadow-lg">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-luxury-border bg-gradient-to-r from-primary to-primary-dark">
            <h2 class="text-2xl font-serif font-bold text-white">Edit User</h2>
            <p class="text-gray-300 mt-1">Update user information and credentials</p>
        </div>

        <form action="{{ route('users.update', $user->user_id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" 
                               name="full_name" 
                               value="{{ old('full_name', $user->full_name) }}"
                               required 
                               class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors @error('full_name') border-red-500 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}"
                               required 
                               class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Account Credentials
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                        <input type="text" 
                               name="username" 
                               value="{{ old('username', $user->username) }}"
                               required 
                               class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors @error('username') border-red-500 @enderror">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               required 
                               class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" 
                               name="password" 
                               class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors @error('password') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Leave blank to keep current password</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="w-full px-4 py-3 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                    </div>
                </div>
            </div>

            <!-- Role Selection -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    User Role
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex cursor-pointer rounded-lg border border-luxury-border p-6 hover:border-accent transition-colors">
                        <input type="radio" name="role" value="admin" {{ old('role', $user->role) === 'admin' ? 'checked' : '' }} class="sr-only peer" required>
                        <div class="flex items-start space-x-4 w-full">
                            <div class="w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center flex-shrink-0 peer-checked:bg-accent peer-checked:text-white transition-colors">
                                <svg class="w-6 h-6 text-accent peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-1">Administrator</h4>
                                <p class="text-sm text-gray-600">Full access to all features</p>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-accent peer-checked:bg-accent flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full hidden peer-checked:block"></div>
                        </div>
                    </label>

                    <label class="relative flex cursor-pointer rounded-lg border border-luxury-border p-6 hover:border-accent transition-colors">
                        <input type="radio" name="role" value="staff" {{ old('role', $user->role) === 'staff' ? 'checked' : '' }} class="sr-only peer">
                        <div class="flex items-start space-x-4 w-full">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 peer-checked:bg-blue-600 transition-colors">
                                <svg class="w-6 h-6 text-blue-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-1">Staff Member</h4>
                                <p class="text-sm text-gray-600">Limited access</p>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full hidden peer-checked:block"></div>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-luxury-border">
                <a href="{{ route('users.show', $user->user_id) }}" class="px-6 py-3 border-2 border-luxury-border text-gray-700 rounded-lg hover:bg-luxury-bg transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-accent to-accent-light text-white rounded-lg hover:shadow-lg transition-all duration-300 font-medium flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Update User</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

