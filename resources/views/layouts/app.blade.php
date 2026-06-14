<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VAULTLAUNDRY') }}</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|instrument-sans:400,500,600,700|instrument-serif:400|jetbrains-mono:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="vault-dashboard bg-[#FAF4EA] font-sans text-neutral-950 antialiased">
        <div
            x-data="{
                sidebarCollapsed: localStorage.getItem('vaultSidebarCollapsed') === 'true',
                pageLoading: false,
                startLoading() {
                    this.pageLoading = true;
                },
                stopLoading() {
                    this.pageLoading = false;
                },
            }"
            x-init="
                $watch('sidebarCollapsed', value => localStorage.setItem('vaultSidebarCollapsed', value));
                window.addEventListener('pageshow', () => stopLoading());
                window.addEventListener('load', () => stopLoading());
                document.addEventListener('click', event => {
                    const link = event.target.closest('a');
                    if (! link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                        return;
                    }

                    const url = new URL(link.href, window.location.href);
                    const sameHash = url.pathname === window.location.pathname && url.search === window.location.search && url.hash;

                    if (url.origin !== window.location.origin || link.target === '_blank' || link.hasAttribute('download') || sameHash) {
                        return;
                    }

                    startLoading();
                });
                document.addEventListener('submit', event => {
                    if (event.target instanceof HTMLFormElement) {
                        startLoading();
                    }
                });
            "
            class="min-h-screen overflow-x-hidden bg-[#FAF4EA]"
        >
            <div class="fixed inset-x-0 top-0 z-[9999] h-1 bg-transparent" aria-hidden="true" data-page-loading-bar>
                <div
                    class="h-full bg-[#FF6626] shadow-[0_0_18px_rgba(255,102,38,0.45)] transition-[width,opacity] duration-200 ease-out"
                    :class="pageLoading ? 'w-11/12 opacity-100' : 'w-0 opacity-0'"
                ></div>
            </div>

            @include('layouts.navigation')

            <div
                class="min-w-0 transition-[padding] duration-200 ease-out"
                :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64'"
            >
                @isset($header)
                    <header class="z-30 border-b border-black/10 bg-[#FAF4EA]/90 backdrop-blur-xl lg:sticky lg:top-0">
                        <div class="px-4 py-4 sm:px-6 md:px-8 md:py-5">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="min-w-0 px-4 py-5 sm:px-6 sm:py-6 md:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
