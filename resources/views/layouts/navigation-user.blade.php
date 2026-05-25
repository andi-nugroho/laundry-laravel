<x-nav-link :href="route('dashboard.user')" :active="request()->routeIs('dashboard.user')">
    {{ __('Dashboard') }}
</x-nav-link>
<x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
    {{ __('Booking Laundry') }}
</x-nav-link>
<span class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 cursor-not-allowed" title="Segera hadir">
    Status Cucian
</span>
<x-nav-link :href="route('monitoring.index')" :active="request()->routeIs('monitoring.*')">
    {{ __('Monitoring Laundry') }}
</x-nav-link>
<span class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 cursor-not-allowed" title="Segera hadir">
    Riwayat
</span>
<x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
    {{ __('Riwayat Pembayaran') }}
</x-nav-link>
<x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
    {{ __('Data Saya') }}
</x-nav-link>
<x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
    {{ __('Profil') }}
</x-nav-link>
