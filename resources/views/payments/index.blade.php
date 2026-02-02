@extends('layouts.app')

@section('title', 'Payments - Hotel Reservation System')

@section('page-title', 'Payments')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-accent">Dashboard</a>
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </li>
    <li class="inline-flex items-center text-accent">
        Payments
    </li>
@endsection

@section('content')
<!-- Header -->
<div class="mb-6">
    <h1 class="text-3xl font-serif font-bold text-primary mb-2">Payment Records</h1>
    <p class="text-gray-600">Track all payment transactions</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-luxury-border p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Payments</p>
                <p class="text-3xl font-serif font-bold text-primary mt-1">{{ $payments->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-luxury-border p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Amount</p>
                <p class="text-3xl font-serif font-bold text-green-600 mt-1">
                    ${{ number_format($payments->sum('amount'), 2) }}
                </p>
            </div>
            <div class="w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-luxury-border p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">This Month</p>
                <p class="text-3xl font-serif font-bold text-accent mt-1">
                    ${{ number_format($payments->where('payment_date', '>=', now()->startOfMonth())->sum('amount'), 2) }}
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-2xl border border-luxury-border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-luxury-bg">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reservation</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Guest</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Method</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-luxury-border">
                @forelse($payments as $payment)
                <tr class="hover:bg-luxury-bg/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">#{{ str_pad($payment->payment_id, 5, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('reservations.show', $payment->reservation->reservation_id) }}" class="text-sm font-medium text-accent hover:text-accent-light">
                            #{{ str_pad($payment->reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accent to-accent-light flex items-center justify-center text-white font-semibold text-sm mr-3">
                                {{ $payment->reservation->guest ? substr($payment->reservation->guest->full_name, 0, 2) : 'N/A' }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $payment->reservation->guest->full_name ?? 'Unknown Guest' }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->reservation->guest->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-lg font-serif font-bold text-green-600">
                            ${{ number_format($payment->amount, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide 
                            {{ $payment->payment_method === 'cash' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $payment->payment_method === 'card' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $payment->payment_method === 'online' ? 'bg-purple-100 text-purple-800' : '' }}">
                            {{ ucfirst($payment->payment_method) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $payment->payment_date->format('M d, Y') }}
                        <div class="text-xs text-gray-500">{{ $payment->payment_date->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('payments.show', $payment->payment_id) }}" class="text-accent hover:text-accent-light font-medium mr-3">
                            View
                        </a>
                        <a href="{{ route('payments.receipt', $payment->payment_id) }}" class="text-primary hover:text-accent font-medium">
                            Receipt
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <p class="text-gray-500 text-xl font-medium mb-2">No payments found</p>
                        <p class="text-gray-400">Payment records will appear here</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
    <div class="px-6 py-4 border-t border-luxury-border">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection