@props([
    'name' => '',
    'class' => 'h-10 w-10',
])

@php
    $normalizedName = str($name)->lower()->toString();
    $icon = match (true) {
        str_contains($normalizedName, 'setrika') && ! str_contains($normalizedName, 'cuci') => 'folded-clothes.webp',
        str_contains($normalizedName, 'cuci setrika') => 'iron.webp',
        str_contains($normalizedName, 'express') => 'detergent.webp',
        str_contains($normalizedName, 'sepatu') => 'package.webp',
        str_contains($normalizedName, 'bedcover') => 'folded-clothes.webp',
        str_contains($normalizedName, 'kering') || str_contains($normalizedName, 'reguler') || str_contains($normalizedName, 'cuci') => 'washing-machine.webp',
        default => 'laundry-basket.webp',
    };
@endphp

<img
    src="{{ asset('assets/'.$icon) }}"
    alt="{{ $name ? 'Icon '.$name : 'Icon layanan laundry' }}"
    {{ $attributes->merge(['class' => $class.' vault-service-icon object-contain']) }}
>
