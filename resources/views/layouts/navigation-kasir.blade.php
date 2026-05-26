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
<x-nav-link :href="route('kasir.riwayat')" :active="request()->routeIs('kasir.riwayat')">
    {{ __('Riwayat') }}
</x-nav-link>
<x-nav-link :href="route('reports.transactions')" :active="request()->routeIs('reports.transactions')">
    {{ __('Laporan Transaksi') }}
</x-nav-link>
