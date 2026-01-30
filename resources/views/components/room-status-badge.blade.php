@php
$statusClasses = [
    'available' => 'bg-green-100 text-green-800',
    'occupied' => 'bg-amber-100 text-amber-800',
    'maintenance' => 'bg-red-100 text-red-800',
];

$statusLabels = [
    'available' => 'Available',
    'occupied' => 'Occupied',
    'maintenance' => 'Maintenance',
];

$class = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
$label = $statusLabels[$status] ?? ucfirst($status);
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide {{ $class }}">
    {{ $label }}
</span>