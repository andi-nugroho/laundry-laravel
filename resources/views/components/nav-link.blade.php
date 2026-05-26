@props(['active'])

@php
$label = trim(preg_replace('/\s+/', ' ', strip_tags((string) $slot)));
$icon = match (true) {
    str_contains($label, 'Dashboard') => 'dashboard',
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
        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            @switch($icon)
                @case('dashboard')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13h7V4H4v9Zm9 7h7v-9h-7v9ZM4 20h7v-5H4v5Zm9-11h7V4h-7v5Z" />
                    @break
                @case('sparkles')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3Zm6 11 .8 2.2L21 17l-2.2.8L18 20l-.8-2.2L15 17l2.2-.8L18 14ZM5 14l.8 2.2L8 17l-2.2.8L5 20l-.8-2.2L2 17l2.2-.8L5 14Z" />
                    @break
                @case('users')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11a4 4 0 1 0-8 0 4 4 0 0 0 8 0Zm-12 9a8 8 0 0 1 16 0M18 8a3 3 0 0 1 0 6m3 6a5.5 5.5 0 0 0-3-4.9" />
                    @break
                @case('calendar')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3v4m10-4v4M4 9h16M6 5h12a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Zm3 8h.01M12 13h.01M15 13h.01M9 16h.01M12 16h.01" />
                    @break
                @case('activity')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h4l2-6 4 12 2-6h6" />
                    @break
                @case('wallet')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7Zm12 5h4m-4 0a2 2 0 1 0 0 .01" />
                    @break
                @case('receipt')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Zm4 6h6M10 13h6M10 17h3" />
                    @break
                @case('chart')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19V5m0 14h16M8 16V9m4 7V6m4 10v-4" />
                    @break
                @case('history')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5v6h6M20 19v-6h-6M5.5 15a7 7 0 0 0 12 2.5M18.5 9a7 7 0 0 0-12-2.5" />
                    @break
                @case('user')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0Zm-10 12a6 6 0 0 1 12 0" />
                    @break
                @default
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 12h.01" />
            @endswitch
        </svg>
    </span>
    <span class="min-w-0 truncate" x-show="! sidebarCollapsed" x-transition.opacity>
        {{ $slot }}
    </span>
</a>
