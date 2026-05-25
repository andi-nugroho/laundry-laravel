@props([
    'label',
    'value',
    'icon' => null,
    'color' => 'indigo',
])

@php
    $colors = [
        'indigo' => 'bg-indigo-50 text-indigo-600 ring-indigo-100',
        'emerald' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
        'amber' => 'bg-amber-50 text-amber-600 ring-amber-100',
        'sky' => 'bg-sky-50 text-sky-600 ring-sky-100',
        'rose' => 'bg-rose-50 text-rose-600 ring-rose-100',
        'slate' => 'bg-slate-50 text-slate-600 ring-slate-100',
    ];
    $colorClass = $colors[$color] ?? $colors['indigo'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-xl ring-1 ring-gray-100']) }}>
    <div class="p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-500 truncate">{{ $label }}</p>
                <p class="mt-2 text-2xl font-semibold tracking-tight text-gray-900">{{ $value }}</p>
            </div>
            @if ($icon)
                <div class="shrink-0 rounded-xl p-3 ring-1 {{ $colorClass }}">
                    {!! $icon !!}
                </div>
            @endif
        </div>
        @isset($footer)
            <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
