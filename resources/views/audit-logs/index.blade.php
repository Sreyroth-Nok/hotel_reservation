@extends('layouts.app')

@section('title', 'Audit Logs - Hotel Reservation System')

@section('page-title', 'Audit Logs')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-accent">Dashboard</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        Audit Logs
    </li>
@endsection

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
    <div class="flex-1">
        <h1 class="text-3xl font-serif font-bold text-primary mb-2">Audit Logs</h1>
        <p class="text-gray-600">Track all system activities and changes</p>
    </div>
</div>

<!-- Filter Form -->
<div class="mb-6 bg-white rounded-xl border border-luxury-border p-4">
    <form action="{{ route('audit-logs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by user or description..."
                       class="w-full px-4 py-2 pl-10 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <select name="action" class="px-4 py-2 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                <option value="">All Actions</option>
                <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Create</option>
                <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
                <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
                <option value="check_in" {{ request('action') === 'check_in' ? 'selected' : '' }}>Check In</option>
                <option value="check_out" {{ request('action') === 'check_out' ? 'selected' : '' }}>Check Out</option>
                <option value="cancel" {{ request('action') === 'cancel' ? 'selected' : '' }}>Cancel</option>
                <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
            </select>
            <select name="model_type" class="px-4 py-2 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors">
                <option value="">All Models</option>
                @foreach($modelTypes as $type)
                <option value="{{ $type['value'] }}" {{ request('model_type') === $type['value'] ? 'selected' : '' }}>
                    {{ $type['label'] }}
                </option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="px-4 py-2 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors"
                   placeholder="From">
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="px-4 py-2 border border-luxury-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent transition-colors"
                   placeholder="To">
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium">
                Filter
            </button>
            <a href="{{ route('audit-logs.index') }}" class="px-6 py-2 border border-luxury-border text-gray-700 rounded-lg hover:bg-luxury-bg transition-colors font-medium">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-luxury-bg">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Model</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-luxury-border">
                @forelse($logs as $log)
                <tr class="hover:bg-luxury-bg/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $log->created_at->format('M d, Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $log->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($log->user)
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-accent to-accent-light flex items-center justify-center text-white text-sm font-semibold mr-2">
                                {{ substr($log->user->full_name ?? 'U', 0, 2) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $log->user->full_name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $log->user->role ?? '' }}</div>
                            </div>
                        </div>
                        @else
                        <span class="text-sm text-gray-400">System</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $colors = [
                                'create' => 'bg-green-100 text-green-800',
                                'update' => 'bg-blue-100 text-blue-800',
                                'delete' => 'bg-red-100 text-red-800',
                                'check_in' => 'bg-green-100 text-green-800',
                                'check_out' => 'bg-gray-100 text-gray-800',
                                'cancel' => 'bg-red-100 text-red-800',
                                'login' => 'bg-green-100 text-green-800',
                                'logout' => 'bg-gray-100 text-gray-800',
                            ];
                            $color = $colors[$log->action] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                            {{ $log->getActionLabel() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $log->getModelName() }}</div>
                        @if($log->model_id)
                        <div class="text-xs text-gray-500">ID: {{ $log->model_id }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600 max-w-xs truncate">
                            {{ $log->description ?? '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $log->ip_address ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('audit-logs.show', $log->log_id) }}" class="text-accent hover:text-accent-light transition-colors font-medium">
                            View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-xl font-medium mb-2">No audit logs found</p>
                        <p class="text-gray-400">System activities will be recorded here</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-luxury-border">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
