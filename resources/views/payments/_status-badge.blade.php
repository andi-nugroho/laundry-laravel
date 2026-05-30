@php
    $statusClasses = [
        'unpaid' => 'bg-red-100 text-red-800',
        'waiting_confirmation' => 'bg-amber-100 text-amber-800',
        'partial' => 'bg-amber-100 text-amber-800',
        'paid' => 'bg-green-100 text-green-800',
    ];

    $statusLabels = [
        'unpaid' => 'Unpaid',
        'waiting_confirmation' => 'Waiting Confirmation',
        'partial' => 'Partial',
        'paid' => 'Paid',
    ];

    $displayStatus = $displayStatus ?? ($statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)));
@endphp

<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-700' }}">
    {{ $displayStatus }}
</span>
