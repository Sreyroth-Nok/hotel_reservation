@php
$statusClasses = [
    'booked' => 'bg-blue-100 text-blue-800',
    'checked_in' => 'bg-green-100 text-green-800',
    'checked_out' => 'bg-gray-100 text-gray-800',
    'cancelled' => 'bg-red-100 text-red-800',
];

$statusLabels = [
    'booked' => 'Booked',
    'checked_in' => 'Checked In',
    'checked_out' => 'Checked Out',
    'cancelled' => 'Cancelled',
];

$class = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
$label = $statusLabels[$status] ?? ucfirst($status);
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide {{ $class }}">
    {{ $label }}
</span>