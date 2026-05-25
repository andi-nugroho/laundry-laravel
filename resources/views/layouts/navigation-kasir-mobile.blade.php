<x-responsive-nav-link :href="route('dashboard.kasir')" :active="request()->routeIs('dashboard.kasir')">
    {{ __('Dashboard') }}
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Booking <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Pelanggan <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Monitoring <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Transaksi <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Riwayat <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
