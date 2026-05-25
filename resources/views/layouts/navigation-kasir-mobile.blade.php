<x-responsive-nav-link :href="route('dashboard.kasir')" :active="request()->routeIs('dashboard.kasir')">
    {{ __('Dashboard') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
    {{ __('Data Pelanggan') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
    {{ __('Booking Laundry') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('monitoring.index')" :active="request()->routeIs('monitoring.*')">
    {{ __('Monitoring Laundry') }}
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Transaksi <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Riwayat <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
