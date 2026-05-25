<x-nav-link :href="route('dashboard.admin')" :active="request()->routeIs('dashboard.admin')">
    {{ __('Dashboard') }}
</x-nav-link>
<x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
    {{ __('Layanan Laundry') }}
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
<span class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 cursor-not-allowed" title="Segera hadir">
    Transaksi
</span>
<span class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 cursor-not-allowed" title="Segera hadir">
    Laporan
</span>
