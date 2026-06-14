@props([
    'label',
    'value',
    'stat' => null,
    'icon' => null,
    'asset' => null,
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

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-[#E8DCCB] bg-[#FFF9F1] shadow-[0_18px_45px_rgba(24,21,18,0.08)]']) }}>
    <div class="p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-neutral-500">{{ $label }}</p>
                <p @if ($stat) data-stat-key="{{ $stat }}" @endif class="mt-2 text-2xl font-black tracking-tight text-neutral-950">{{ $value }}</p>
            </div>
            @if ($asset)
                <div class="shrink-0 rounded-xl p-3 ring-1 {{ $colorClass }}">
                    <img src="{{ asset($asset) }}" alt="" class="h-7 w-7 object-contain">
                </div>
            @elseif ($icon)
                <div class="shrink-0 rounded-xl p-3 ring-1 {{ $colorClass }}">
                    {!! $icon !!}
                </div>
            @endif
        </div>
        @isset($footer)
            <div class="mt-4 border-t border-[#E8DCCB] pt-4 text-xs font-medium text-neutral-500">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
