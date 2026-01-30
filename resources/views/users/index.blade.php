@extends('layouts.app')

@section('title', 'Users Management - Hotel Reservation System')

@section('page-title', 'Users Management')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-accent">Dashboard</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        Users
    </li>
@endsection

@section('content')
<!-- Header with Search and Actions -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
    <div class="flex-1">
        <h1 class="text-3xl font-serif font-bold text-primary mb-2">User Management</h1>
        <p class="text-gray-600">Manage admin and staff users</p>
    </div>
    
    <div class="flex items-center space-x-3">
        <!-- Search Form -->
        <form method="GET" action="{{ route('users.index') }}" class="flex items-center space-x-2">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Search users..." 
                   class="px-4 py-2 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent">
            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </form>

        <!-- Filter Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="px-4 py-2 border border-luxury-border rounded-lg hover:bg-luxury-bg transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span class="font-medium">Filter by Role</span>
            </button>
            
            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-luxury-border py-2 z-10">
                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-luxury-bg {{ !request('role') ? 'bg-luxury-bg font-semibold' : '' }}">All Users</a>
                <a href="{{ route('users.index', ['role' => 'admin']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-luxury-bg {{ request('role') === 'admin' ? 'bg-luxury-bg font-semibold' : '' }}">Administrators</a>
                <a href="{{ route('users.index', ['role' => 'staff']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-luxury-bg {{ request('role') === 'staff' ? 'bg-luxury-bg font-semibold' : '' }}">Staff</a>
            </div>
        </div>

        <!-- Add User Button -->
        <a href="{{ route('users.create') }}" class="px-6 py-2 bg-gradient-to-r from-accent to-accent-light text-white rounded-lg hover:shadow-lg transition-all duration-300 font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Add User</span>
        </a>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-luxury-border p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Total Users</p>
                <p class="text-3xl font-serif font-bold text-primary">{{ $stats['total_users'] }}</p>
            </div>
            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-luxury-border p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Administrators</p>
                <p class="text-3xl font-serif font-bold text-accent">{{ $stats['admin_users'] }}</p>
            </div>
            <div class="w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-luxury-border p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Staff Members</p>
                <p class="text-3xl font-serif font-bold text-blue-600">{{ $stats['staff_users'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-luxury-border p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">New This Month</p>
                <p class="text-3xl font-serif font-bold text-green-600">{{ $stats['new_this_month'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-luxury-bg">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-luxury-border">
                @forelse($users as $user)
                <tr class="hover:bg-luxury-bg/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-accent to-accent-light flex items-center justify-center text-white font-bold text-lg mr-3">
                                {{ substr($user->full_name, 0, 2) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->full_name }}</div>
                                <div class="text-xs text-gray-500">ID: #{{ str_pad($user->user_id, 5, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-900">{{ $user->username }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600">{{ $user->email }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600">{{ $user->phone }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide 
                            {{ $user->role === 'admin' ? 'bg-accent/10 text-accent' : 'bg-blue-100 text-blue-800' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('users.show', $user->user_id) }}" class="text-primary hover:text-accent transition-colors font-medium">
                                View
                            </a>
                            <a href="{{ route('users.edit', $user->user_id) }}" class="text-accent hover:text-accent-light transition-colors font-medium">
                                Edit
                            </a>
                            @if($user->user_id !== auth()->id())
                            <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors font-medium">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-xl font-medium mb-2">No users found</p>
                        <p class="text-gray-400 mb-6">Try adjusting your search or filters</p>
                        <a href="{{ route('users.create') }}" class="inline-flex items-center px-6 py-3 bg-accent text-white rounded-lg hover:bg-accent-light transition-colors font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New User
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-luxury-border">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection