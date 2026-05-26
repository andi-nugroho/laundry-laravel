@props([
    'label',
    'value' => null,
])

<div>
    <div class="text-[0.68rem] font-black uppercase tracking-[0.12em] text-neutral-400">{{ $label }}</div>
    <div class="mt-1 text-sm font-semibold text-neutral-800">
        {{ $slot->isEmpty() ? ($value ?? '-') : $slot }}
    </div>
</div>
