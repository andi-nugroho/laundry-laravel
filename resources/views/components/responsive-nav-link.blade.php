@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-2xl bg-[#FF6626] px-4 py-3 text-start text-sm font-black text-white shadow-lg shadow-orange-500/20 outline-none transition duration-200'
            : 'block w-full rounded-2xl border border-transparent px-4 py-3 text-start text-sm font-bold text-neutral-600 outline-none transition duration-200 hover:border-[#E8DCCB] hover:bg-[#FAF4EA] hover:text-[#FF6626] focus:bg-[#FAF4EA] focus:text-[#FF6626]';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
