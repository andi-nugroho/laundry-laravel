@php
    $statusClasses = [
        'booking_masuk' => 'bg-blue-100 text-blue-800',
        'diterima' => 'bg-indigo-100 text-indigo-800',
        'dicuci' => 'bg-cyan-100 text-cyan-800',
        'dikeringkan' => 'bg-sky-100 text-sky-800',
        'disetrika' => 'bg-amber-100 text-amber-800',
        'selesai' => 'bg-green-100 text-green-800',
        'diambil' => 'bg-gray-100 text-gray-700',
        'dibatalkan' => 'bg-red-100 text-red-800',
    ];
@endphp

<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-700' }}">
    {{ str_replace('_', ' ', ucfirst($status)) }}
</span>
