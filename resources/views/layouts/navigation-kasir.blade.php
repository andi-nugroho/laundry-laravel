<x-nav-link :href="route('dashboard.kasir')" :active="request()->routeIs('dashboard.kasir')">
    {{ __('Dashboard') }}
</x-nav-link>
<x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
    {{ __('Data Pelanggan') }}
</x-nav-link>
<x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
    {{ __('Booking Laundry') }}
</x-nav-link>
<x-nav-link :href="route('monitoring.index')" :active="request()->routeIs('monitoring.*')">
    {{ __('Monitoring Laundry') }}
</x-nav-link>
<x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
    {{ __('Transaksi Pembayaran') }}
</x-nav-link>
<span class="flex w-full cursor-not-allowed items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-bold text-neutral-400" title="Segera hadir">
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#FBF3E7] text-neutral-400 ring-1 ring-[#E8DCCB]">
        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5v6h6M20 19v-6h-6M5.5 15a7 7 0 0 0 12 2.5M18.5 9a7 7 0 0 0-12-2.5" />
        </svg>
    </span>
    <span class="min-w-0 truncate" x-show="! sidebarCollapsed" x-transition.opacity>Riwayat</span>
</span>
<x-nav-link :href="route('reports.transactions')" :active="request()->routeIs('reports.transactions')">
    {{ __('Laporan Transaksi') }}
</x-nav-link>
