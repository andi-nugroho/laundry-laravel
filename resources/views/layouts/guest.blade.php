<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">
        <meta name="developer" content="Andi Nugroho, andidelouise, andidev">

        <title>{{ config('app.name', 'VAULTLAUNDRY') }}</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|instrument-sans:400,500,600,700|instrument-serif:400|jetbrains-mono:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/guest.js'])
    </head>
    <body class="bg-[#FAF4EA] font-sans text-neutral-950 antialiased">
        <main class="noise-overlay relative min-h-screen overflow-hidden px-4 py-6 sm:px-6 lg:px-8">
            <div class="absolute inset-0 laundry-grid opacity-70"></div>

            <div class="relative z-10 mx-auto flex min-h-[calc(100vh-3rem)] w-full max-w-6xl items-center justify-center">
                <div class="grid w-full overflow-hidden rounded-[2rem] border border-black/10 bg-[#fffaf4]/90 shadow-2xl shadow-neutral-950/10 backdrop-blur lg:grid-cols-[0.95fr_1.05fr]">
                    <section class="px-6 py-8 sm:px-10 lg:px-12 lg:py-12">
                        <a href="{{ url('/') }}" class="mb-10 inline-flex items-center gap-3">
                            <x-application-logo class="h-11 w-11 rounded-xl" />
                            <span class="text-sm font-black tracking-[0.18em] text-neutral-950">VAULTLAUNDRY</span>
                        </a>

                        {{ $slot }}
                    </section>

                    <aside class="relative hidden overflow-hidden bg-neutral-950 p-10 text-white lg:block">
                        <div class="absolute inset-0 opacity-30 laundry-grid"></div>
                        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-[#FF6626]/30 blur-3xl"></div>
                        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-orange-200/10 blur-3xl"></div>

                        <div class="relative z-10 flex h-full flex-col justify-between">
                            <div>
                                <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-orange-300">Laundry command center</p>
                                <h2 class="mt-5 font-display text-6xl leading-none text-white">
                                    Booking, status, payment, nota.
                                </h2>
                                <p class="mt-6 max-w-md text-base leading-7 text-white/60">
                                    Masuk ke VAULTLAUNDRY untuk mengelola pelanggan, memantau proses laundry, dan melihat transaksi dengan tampilan operasional yang rapi.
                                </p>
                            </div>

                            <div class="space-y-4">
                                <div class="rounded-[1.5rem] border border-white/10 bg-white/[0.06] p-5 backdrop-blur">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-widest text-white/45">Active booking</p>
                                            <p class="mt-1 text-2xl font-black">LDY-2026-0001</p>
                                        </div>
                                        <span class="rounded-full bg-[#FF6626] px-3 py-1 text-xs font-black">DICUCI</span>
                                    </div>
                                    <div class="mt-5 h-2 rounded-full bg-white/10">
                                        <div class="h-2 w-3/5 rounded-full bg-[#FF6626]"></div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div class="rounded-2xl bg-white/[0.06] p-4">
                                        <p class="font-display text-3xl">8</p>
                                        <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-white/45">Status</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/[0.06] p-4">
                                        <p class="font-display text-3xl">3</p>
                                        <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-white/45">Payment</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/[0.06] p-4">
                                        <p class="font-display text-3xl">A4</p>
                                        <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-white/45">Invoice</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </main>
    </body>
</html>
