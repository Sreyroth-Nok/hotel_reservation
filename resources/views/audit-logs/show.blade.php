@extends('layouts.app')

@section('title', 'Audit Log Details - Hotel Reservation System')

@section('page-title', 'Audit Log Details')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-accent">Dashboard</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center">
        <a href="{{ route('audit-logs.index') }}" class="text-gray-500 hover:text-accent">Audit Logs</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        #{{ $log->log_id }}
    </li>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Log Info -->
        <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
            <div class="px-6 py-4 border-b border-luxury-border bg-gradient-to-r from-primary to-primary-dark">
                <h2 class="text-xl font-serif font-bold text-white">
                    Audit Log #{{ $log->log_id }}
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Action Info -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Action Details</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-500">Action:</span>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $log->getActionColor() }}-100 text-{{ $log->getActionColor() }}-800">
                                    {{ $log->getActionLabel() }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Model:</span>
                                <span class="ml-2 text-sm font-medium text-gray-900">{{ $log->getModelName() }}</span>
                            </div>
                            @if($log->model_id)
                            <div>
                                <span class="text-sm text-gray-500">Record ID:</span>
                                <span class="ml-2 text-sm font-medium text-gray-900">{{ $log->model_id }}</span>
                            </div>
                            @endif
                            <div>
                                <span class="text-sm text-gray-500">Timestamp:</span>
                                <span class="ml-2 text-sm font-medium text-gray-900">{{ $log->created_at->format('M d, Y h:i:s A') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">User Information</h3>
                        @if($log->user)
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-accent to-accent-light flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                                {{ substr($log->user->full_name ?? 'U', 0, 2) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $log->user->full_name ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-600">{{ $log->user->email ?? '' }}</p>
                                <p class="text-xs text-gray-500 uppercase">{{ $log->user->role ?? '' }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-400">System / Unknown User</p>
                        @endif
                    </div>
                </div>

                @if($log->description)
                <hr class="my-6 border-luxury-border">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Description</h3>
                    <p class="text-gray-700">{{ $log->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Values Comparison -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Old Values -->
            <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
                <div class="px-6 py-4 border-b border-luxury-border bg-red-50">
                    <h3 class="text-lg font-semibold text-red-800">Old Values</h3>
                </div>
                <div class="p-6">
                    @if($log->old_values)
                    <pre class="text-sm text-gray-700 bg-gray-50 p-4 rounded-lg overflow-x-auto">{{ $log->getFormattedOldValues() }}</pre>
                    @else
                    <p class="text-gray-400 text-sm">No previous values recorded</p>
                    @endif
                </div>
            </div>

            <!-- New Values -->
            <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
                <div class="px-6 py-4 border-b border-luxury-border bg-green-50">
                    <h3 class="text-lg font-semibold text-green-800">New Values</h3>
                </div>
                <div class="p-6">
                    @if($log->new_values)
                    <pre class="text-sm text-gray-700 bg-gray-50 p-4 rounded-lg overflow-x-auto">{{ $log->getFormattedNewValues() }}</pre>
                    @else
                    <p class="text-gray-400 text-sm">No new values recorded</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Technical Details -->
        <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
            <div class="px-6 py-4 border-b border-luxury-border">
                <h2 class="text-lg font-serif font-semibold text-primary">Technical Details</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm text-gray-500">IP Address</label>
                    <p class="text-sm font-medium text-gray-900">{{ $log->ip_address ?? 'Not recorded' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">User Agent</label>
                    <p class="text-xs text-gray-600 break-all">{{ $log->user_agent ?? 'Not recorded' }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
            <div class="px-6 py-4 border-b border-luxury-border">
                <h2 class="text-lg font-serif font-semibold text-primary">Actions</h2>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('audit-logs.index') }}" class="block w-full px-4 py-3 border-2 border-luxury-border text-gray-700 rounded-lg hover:bg-luxury-bg transition-colors font-medium text-center">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
