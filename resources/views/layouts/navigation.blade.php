@php
    $user = Auth::user();
    $dashboardRoute = $user->dashboardRouteName();
    $navPartial = match ($user->role) {
        \App\Models\User::ROLE_ADMIN => 'layouts.navigation-admin',
        \App\Models\User::ROLE_KASIR => 'layouts.navigation-kasir',
        default => 'layouts.navigation-user',
    };
    $navPartialMobile = match ($user->role) {
        \App\Models\User::ROLE_ADMIN => 'layouts.navigation-admin-mobile',
        \App\Models\User::ROLE_KASIR => 'layouts.navigation-kasir-mobile',
        default => 'layouts.navigation-user-mobile',
    };
@endphp

<div x-data="{ mobileOpen: false }">
    <aside
        class="hidden border-r border-black/10 bg-[#FFF9F1]/92 shadow-xl shadow-neutral-950/5 backdrop-blur-xl transition-[width] duration-200 ease-out lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 lg:flex lg:flex-col"
        :class="sidebarCollapsed ? 'lg:w-20' : 'lg:w-64'"
    >
        <div class="flex h-full min-w-0 flex-col transition-[padding] duration-200" :class="sidebarCollapsed ? 'px-3 py-5' : 'px-4 py-6'">
            <div class="flex items-center gap-2" :class="sidebarCollapsed ? 'flex-col justify-center gap-3' : 'justify-between'">
                <a href="{{ route($dashboardRoute) }}" class="flex min-w-0 items-center gap-3 rounded-2xl py-2" :class="sidebarCollapsed ? 'justify-center' : 'px-1'">
                    <x-application-logo class="h-11 w-11 shrink-0 rounded-xl" />
                    <div class="min-w-0" x-show="! sidebarCollapsed">
                        <div class="truncate text-sm font-black tracking-[0.18em] text-neutral-950">VAULTLAUNDRY</div>
                        <div class="mt-0.5 truncate text-xs font-semibold uppercase tracking-widest text-[#FF6626]">{{ $user->role }}</div>
                    </div>
                </a>

                <button
                    type="button"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-black/10 bg-[#FAF4EA] text-neutral-600 transition hover:border-[#FF6626]/40 hover:text-[#FF6626]"
                    @click="sidebarCollapsed = ! sidebarCollapsed"
                    aria-label="Toggle sidebar"
                    data-sidebar-toggle
                >
                    <svg class="h-4 w-4 transition-transform duration-200" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <nav class="mt-8 flex-1 space-y-1 overflow-y-auto overflow-x-hidden">
                @include($navPartial)
            </nav>

            <div class="mt-6 rounded-[1.35rem] border border-black/10 bg-[#FAF4EA]/85 transition-[padding] duration-200" :class="sidebarCollapsed ? 'p-2' : 'p-4'">
                <div class="flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FF6626] text-sm font-black uppercase text-white">
                        {{ str($user->name)->substr(0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1" x-show="! sidebarCollapsed">
                        <div class="truncate text-sm font-black text-neutral-950">{{ $user->name }}</div>
                        <div class="truncate text-xs font-medium text-neutral-500">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-2" x-show="! sidebarCollapsed">
                    <a href="{{ route('profile.edit') }}" class="rounded-full border border-black/10 bg-[#fffaf4] px-3 py-2 text-center text-xs font-bold text-neutral-700 transition hover:border-[#FF6626]/40 hover:text-[#FF6626]">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-full bg-neutral-950 px-3 py-2 text-xs font-bold text-white transition hover:bg-[#FF6626]">
                            Log Out
                        </button>
                    </form>
                </div>

                <div class="mt-3 space-y-2" x-show="sidebarCollapsed">
                    <a href="{{ route('profile.edit') }}" class="flex h-10 w-full items-center justify-center rounded-2xl border border-black/10 bg-[#fffaf4] text-xs font-black text-neutral-700 transition hover:border-[#FF6626]/40 hover:text-[#FF6626]" title="Profile">
                        <svg class="h-[17px] w-[17px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0Zm-10 12a6 6 0 0 1 12 0" />
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex h-10 w-full items-center justify-center rounded-2xl bg-neutral-950 text-xs font-black text-white transition hover:bg-[#FF6626]" title="Log Out">
                            <svg class="h-[17px] w-[17px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17l5-5-5-5m5 5H9m3 7H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <div class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-black/10 bg-[#FAF4EA]/95 px-4 backdrop-blur-xl lg:hidden">
        <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-3">
            <x-application-logo class="h-9 w-9 rounded-xl" />
            <span class="text-xs font-black tracking-[0.16em] text-neutral-950">VAULTLAUNDRY</span>
        </a>

        <button
            type="button"
            class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-black/10 bg-[#fffaf4] text-neutral-700 transition hover:border-[#FF6626]/40 hover:text-[#FF6626]"
            @click="mobileOpen = true"
            aria-label="Open navigation"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>
    </div>

    <div x-show="mobileOpen" class="fixed inset-0 z-50 lg:hidden" style="display: none;">
        <div
            x-show="mobileOpen"
            class="absolute inset-0 bg-neutral-950/40 backdrop-blur-sm"
            @click="mobileOpen = false"
        ></div>

        <aside
            x-show="mobileOpen"
            class="relative flex h-full w-[min(22rem,calc(100vw-2rem))] flex-col border-r border-black/10 bg-[#FFF9F1] p-5 shadow-2xl"
        >
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-3">
                    <x-application-logo class="h-10 w-10 rounded-xl" />
                    <div>
                        <div class="text-sm font-black tracking-[0.18em] text-neutral-950">VAULTLAUNDRY</div>
                        <div class="text-xs font-semibold uppercase tracking-widest text-[#FF6626]">{{ $user->role }}</div>
                    </div>
                </a>
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-black/10 text-neutral-700 transition hover:text-[#FF6626]"
                    @click="mobileOpen = false"
                    aria-label="Close navigation"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="mt-8 flex-1 space-y-1 overflow-y-auto">
                @include($navPartialMobile)
            </nav>

            <div class="mt-6 border-t border-black/10 pt-4">
                <div class="mb-4">
                    <div class="text-sm font-black text-neutral-950">{{ $user->name }}</div>
                    <div class="text-xs font-medium text-neutral-500">{{ $user->email }}</div>
                </div>

                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="block w-full rounded-2xl bg-neutral-950 px-4 py-3 text-left text-sm font-bold text-white transition hover:bg-[#FF6626]">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </aside>
    </div>
</div>
