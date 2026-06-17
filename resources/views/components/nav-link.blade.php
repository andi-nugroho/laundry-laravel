@props(['active'])

@php
$label = trim(preg_replace('/\s+/', ' ', strip_tags((string) $slot)));
$icon = match (true) {
    str_contains($label, 'Dashboard') => 'dashboard',
    str_contains($label, 'Pesan') => 'cart',
    str_contains($label, 'Layanan') => 'sparkles',
    str_contains($label, 'Pelanggan') || str_contains($label, 'Data Saya') => 'users',
    str_contains($label, 'Booking') => 'calendar',
    str_contains($label, 'Monitoring') || str_contains($label, 'Status') => 'activity',
    str_contains($label, 'Pembayaran') => 'wallet',
    str_contains($label, 'Transaksi') => 'receipt',
    str_contains($label, 'Pendapatan') => 'chart',
    str_contains($label, 'Riwayat') => 'history',
    str_contains($label, 'Profil') || str_contains($label, 'Profile') => 'user',
    default => 'dot',
};

$classes = ($active ?? false)
            ? 'flex w-full items-center gap-3 rounded-2xl bg-[#FF6626] px-3 py-2.5 text-sm font-black text-white shadow-lg shadow-orange-500/20 outline-none transition duration-200'
            : 'flex w-full items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-bold text-neutral-600 outline-none transition duration-200 hover:bg-[#FAF4EA] hover:text-[#FF6626] focus:bg-[#FAF4EA] focus:text-[#FF6626]';

$iconClasses = ($active ?? false)
            ? 'bg-white/20 text-white'
            : 'bg-[#FBF3E7] text-[#FF6626] ring-1 ring-[#E8DCCB]';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'title' => $label]) }} :class="sidebarCollapsed ? 'justify-center px-2' : ''">
    <span class="{{ $iconClasses }} flex h-9 w-9 shrink-0 items-center justify-center rounded-xl transition duration-200">
        <x-nav-icon :icon="$icon" />
    </span>
    <span class="min-w-0 truncate" x-show="! sidebarCollapsed">
        {{ $slot }}
    </span>
</a>
