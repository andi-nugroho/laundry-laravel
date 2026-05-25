<x-responsive-nav-link :href="route('dashboard.user')" :active="request()->routeIs('dashboard.user')">
    {{ __('Dashboard') }}
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Booking <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Status Cucian <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link href="#" :active="false">
    Riwayat <span class="text-xs text-gray-400">(segera)</span>
</x-responsive-nav-link>
<x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
    {{ __('Profil') }}
</x-responsive-nav-link>
