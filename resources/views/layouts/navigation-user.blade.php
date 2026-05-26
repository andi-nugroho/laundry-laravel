<x-nav-link :href="route('dashboard.user')" :active="request()->routeIs('dashboard.user')">
    {{ __('Dashboard') }}
</x-nav-link>
<x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
    {{ __('Booking Laundry') }}
</x-nav-link>
<x-nav-link :href="route('user.status-cucian')" :active="request()->routeIs('user.status-cucian')">
    {{ __('Status Cucian') }}
</x-nav-link>
<x-nav-link :href="route('monitoring.index')" :active="request()->routeIs('monitoring.*')">
    {{ __('Monitoring Laundry') }}
</x-nav-link>
<x-nav-link :href="route('user.riwayat')" :active="request()->routeIs('user.riwayat')">
    {{ __('Riwayat') }}
</x-nav-link>
<x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
    {{ __('Riwayat Pembayaran') }}
</x-nav-link>
<x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
    {{ __('Data Saya') }}
</x-nav-link>
<x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
    {{ __('Profil') }}
</x-nav-link>
