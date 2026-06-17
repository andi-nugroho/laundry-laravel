@props(['icon' => 'dot'])

<svg {{ $attributes->merge(['class' => 'h-[18px] w-[18px]']) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
    @switch($icon)
        @case('dashboard')
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13h7V4H4v9Zm9 7h7v-9h-7v9ZM4 20h7v-5H4v5Zm9-11h7V4h-7v5Z" />
            @break
        @case('sparkles')
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3Zm6 11 .8 2.2L21 17l-2.2.8L18 20l-.8-2.2L15 17l2.2-.8L18 14ZM5 14l.8 2.2L8 17l-2.2.8L5 20l-.8-2.2L2 17l2.2-.8L5 14Z" />
            @break
        @case('cart')
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h2l2.2 9.5a2 2 0 0 0 2 1.5h6.9a2 2 0 0 0 1.9-1.4L21 8H7M10 20h.01M17 20h.01" />
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
