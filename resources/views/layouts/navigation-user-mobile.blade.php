<x-responsive-nav-link :href="route('dashboard.user')" :active="request()->routeIs('dashboard.user')">
    {{ __('Dashboard') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('user.orders.create')" :active="request()->routeIs('user.orders.*')">
    {{ __('Pesan Laundry') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
    {{ __('Booking Laundry') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('user.status-cucian')" :active="request()->routeIs('user.status-cucian')">
    {{ __('Status Cucian') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('monitoring.index')" :active="request()->routeIs('monitoring.*')">
    {{ __('Monitoring Laundry') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('user.riwayat')" :active="request()->routeIs('user.riwayat')">
    {{ __('Riwayat') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
    {{ __('Riwayat Pembayaran') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
    {{ __('Data Saya') }}
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
    {{ __('Profil') }}
</x-responsive-nav-link>
