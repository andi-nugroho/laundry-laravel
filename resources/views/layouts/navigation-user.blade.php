<x-nav-link :href="route('dashboard.user')" :active="request()->routeIs('dashboard.user')">
    {{ __('Dashboard') }}
</x-nav-link>
<span class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 cursor-not-allowed" title="Segera hadir">
    Booking
</span>
<span class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 cursor-not-allowed" title="Segera hadir">
    Status Cucian
</span>
<span class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-400 cursor-not-allowed" title="Segera hadir">
    Riwayat
</span>
<x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
    {{ __('Profil') }}
</x-nav-link>
