<x-responsive-nav-link :href="route('dashboard.admin')" :active="request()->routeIs('dashboard.admin')">
    {{ __('Dashboard') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
    {{ __('Layanan Laundry') }}
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Booking <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Monitoring <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Transaksi <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Laporan <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
