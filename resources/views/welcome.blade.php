<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>VAULTLAUNDRY - Laundry Booking, Monitoring, dan Pembayaran</title>
        <meta name="description" content="VAULTLAUNDRY membantu pelanggan booking laundry, memantau status cucian, membayar transaksi, dan mencetak nota secara praktis.">

        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|instrument-sans:400,500,600,700|instrument-serif:400|jetbrains-mono:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FAF4EA] font-sans text-neutral-950 antialiased">
        <main class="noise-overlay relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 laundry-grid opacity-70"></div>

            <header
                x-data="{ scrolled: window.scrollY > 20 }"
                x-init="scrolled = window.scrollY > 20"
                @scroll.window="scrolled = window.scrollY > 20"
                class="fixed left-0 right-0 top-0 z-50 px-4"
            >
                <nav
                    class="mx-auto flex items-center justify-between transition-all duration-500 ease-out"
                    :class="scrolled
                        ? 'mt-4 h-16 max-w-6xl rounded-full border border-black/10 bg-[#FAF4EA]/85 px-4 shadow-lg shadow-neutral-950/10 backdrop-blur-xl sm:px-6'
                        : 'mt-0 h-20 max-w-7xl rounded-none border border-transparent bg-transparent px-2 shadow-none backdrop-blur-0 sm:px-6 lg:px-8'"
                >
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <img src="{{ asset('logo.svg') }}" alt="VAULTLAUNDRY" class="h-10 w-10 rounded-xl">
                        <span class="text-sm font-black tracking-[0.18em] text-neutral-950 sm:text-base">VAULTLAUNDRY</span>
                    </a>

                    <div class="hidden items-center gap-8 text-sm font-semibold text-neutral-600 md:flex">
                        <a href="#fitur" class="transition hover:text-[#FF6626]">Fitur</a>
                        <a href="#alur" class="transition hover:text-[#FF6626]">Alur</a>
                        <a href="#layanan" class="transition hover:text-[#FF6626]">Layanan</a>
                    </div>

                    <div class="flex items-center gap-2">
                        @auth
                            <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="vault-button px-4 py-2 text-xs sm:px-5">
                                Dashboard
                            </a>
                        @else
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="hidden rounded-full px-4 py-2 text-xs font-bold text-neutral-700 transition hover:text-[#FF6626] sm:inline-flex">
                                    Login
                                </a>
                            @endif
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="vault-button px-4 py-2 text-xs sm:px-5">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </nav>
            </header>

            <section class="relative z-10 vault-section pt-32 lg:pt-40">
                <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
                    <div class="max-w-3xl">
                        <p class="font-mono-vault mb-6 inline-flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.28em] text-[#FF6626]">
                            <span class="h-px w-10 bg-[#FF6626]"></span>
                            Laundry system for modern teams
                        </p>
                        <h1 class="font-display text-6xl leading-none tracking-normal text-neutral-950 sm:text-7xl lg:text-8xl">
                            Booking laundry jadi rapi dari masuk sampai lunas.
                        </h1>
                        <p class="mt-8 max-w-2xl text-lg leading-8 text-neutral-600 sm:text-xl">
                            VAULTLAUNDRY menyatukan data pelanggan, booking, monitoring status cucian, transaksi pembayaran, invoice, dan laporan pendapatan dalam satu sistem yang siap dipakai.
                        </p>

                        <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                            @auth
                                <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="vault-button">
                                    Buka Dashboard
                                </a>
                            @else
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="vault-button">
                                        Mulai Booking
                                    </a>
                                @endif
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="vault-button-secondary">
                                        Login Sistem
                                    </a>
                                @endif
                            @endauth
                        </div>

                        <div class="mt-12 grid max-w-2xl grid-cols-3 gap-4 border-y border-black/10 py-6">
                            <div>
                                <p class="font-display text-4xl leading-none text-neutral-950">8</p>
                                <p class="mt-1 text-xs font-bold uppercase tracking-widest text-neutral-500">Status laundry</p>
                            </div>
                            <div>
                                <p class="font-display text-4xl leading-none text-neutral-950">3</p>
                                <p class="mt-1 text-xs font-bold uppercase tracking-widest text-neutral-500">Metode bayar</p>
                            </div>
                            <div>
                                <p class="font-display text-4xl leading-none text-neutral-950">A4</p>
                                <p class="mt-1 text-xs font-bold uppercase tracking-widest text-neutral-500">Nota print</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative lg:-translate-y-12 xl:-translate-y-16">
                        <div class="vault-card hover-lift overflow-hidden rounded-[2rem]">
                            <div class="border-b border-black/10 bg-neutral-950 p-6 text-white">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('logo.svg') }}" alt="VAULTLAUNDRY" class="h-12 w-12 rounded-2xl">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-orange-200">Live Operation</p>
                                            <p class="text-xl font-black">LDY-2026-0001</p>
                                        </div>
                                    </div>
                                    <span class="rounded-full bg-[#FF6626] px-3 py-1 text-xs font-bold">Dicuci</span>
                                </div>
                            </div>

                            <div class="space-y-5 p-6">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl bg-[#fff3ea] p-4">
                                        <p class="text-xs font-bold uppercase tracking-widest text-neutral-500">Customer</p>
                                        <p class="mt-2 text-lg font-black text-neutral-950">Nadia Putri</p>
                                    </div>
                                    <div class="rounded-2xl bg-neutral-50 p-4">
                                        <p class="text-xs font-bold uppercase tracking-widest text-neutral-500">Layanan</p>
                                        <p class="mt-2 text-lg font-black text-neutral-950">Cuci Setrika</p>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-black/10 p-4">
                                    <div class="flex items-center justify-between text-sm font-semibold">
                                        <span>Booking masuk</span>
                                        <span>Diterima</span>
                                        <span>Dicuci</span>
                                        <span>Selesai</span>
                                    </div>
                                    <div class="mt-4 h-2 rounded-full bg-neutral-100">
                                        <div class="h-2 w-3/5 rounded-full bg-[#FF6626]"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between rounded-2xl bg-neutral-950 p-5 text-white">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-widest text-white/50">Total tagihan</p>
                                        <p class="mt-1 text-3xl font-black">Rp 72.000</p>
                                    </div>
                                    <span class="rounded-full bg-white px-4 py-2 text-xs font-black text-neutral-950">PAID</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="fitur" class="relative z-10 vault-section py-24">
                <div class="max-w-3xl">
                    <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-[#FF6626]">Core system</p>
                    <h2 class="mt-4 font-display text-5xl leading-tight text-neutral-950 sm:text-6xl">Fitur utama yang menjaga operasional tetap terkendali.</h2>
                </div>

                <div class="mt-12 grid gap-5 md:grid-cols-2 xl:grid-cols-5">
                    @foreach ([
                        ['title' => 'Booking', 'text' => 'Catat pelanggan, layanan, berat, tanggal masuk, estimasi selesai, dan total harga otomatis.'],
                        ['title' => 'Monitoring', 'text' => 'Pantau status dari booking_masuk sampai diambil atau dibatalkan.'],
                        ['title' => 'Pembayaran', 'text' => 'Proses cash, transfer, ewallet, partial, paid, dan hitung kembalian.'],
                        ['title' => 'Cetak Nota', 'text' => 'Invoice rapi siap print dengan rincian booking, layanan, dan kasir.'],
                        ['title' => 'Laporan', 'text' => 'Filter transaksi dan ringkasan pendapatan untuk admin dan kasir.'],
                    ] as $feature)
                        <article class="vault-card hover-lift p-6">
                            <div class="mb-6 flex h-11 w-11 items-center justify-center rounded-2xl bg-[#FF6626] text-sm font-black text-white">
                                {{ substr($feature['title'], 0, 1) }}
                            </div>
                            <h3 class="text-lg font-black text-neutral-950">{{ $feature['title'] }}</h3>
                            <p class="mt-3 text-sm leading-6 text-neutral-600">{{ $feature['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section id="alur" class="relative z-10 bg-neutral-950 py-24 text-white">
                <div class="vault-section">
                    <div class="grid gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:items-end">
                        <div>
                            <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-orange-300">Workflow</p>
                            <h2 class="mt-4 font-display text-5xl leading-tight sm:text-6xl">Alur laundry dari meja kasir sampai nota.</h2>
                        </div>
                        <p class="max-w-2xl text-lg leading-8 text-white/65">
                            Dibuat mengikuti proses laundry harian: pelanggan datang atau pickup, kasir membuat booking, status dipantau, pembayaran diproses, lalu nota dicetak.
                        </p>
                    </div>

                    <div class="mt-14 grid gap-4 md:grid-cols-4">
                        @foreach ([
                            ['step' => '01', 'title' => 'Pelanggan', 'text' => 'Data pelanggan tersimpan dan bisa terhubung ke akun user.'],
                            ['step' => '02', 'title' => 'Booking', 'text' => 'Kode booking, estimasi selesai, dan total harga dihitung otomatis.'],
                            ['step' => '03', 'title' => 'Proses', 'text' => 'Admin dan kasir mengubah status sesuai progres laundry.'],
                            ['step' => '04', 'title' => 'Bayar', 'text' => 'Payment, invoice, dan laporan pendapatan siap dicek.'],
                        ] as $item)
                            <div class="rounded-[1.5rem] border border-white/10 bg-white/[0.04] p-6">
                                <p class="font-mono-vault text-xs font-bold text-orange-300">{{ $item['step'] }}</p>
                                <h3 class="mt-8 text-xl font-black">{{ $item['title'] }}</h3>
                                <p class="mt-3 text-sm leading-6 text-white/60">{{ $item['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="layanan" class="relative z-10 vault-section py-24">
                <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr]">
                    <div>
                        <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-[#FF6626]">Laundry services</p>
                        <h2 class="mt-4 font-display text-5xl leading-tight text-neutral-950 sm:text-6xl">Layanan yang umum dipakai pelanggan harian.</h2>
                        <p class="mt-6 text-lg leading-8 text-neutral-600">
                            Section ini disiapkan sebagai etalase layanan. Data asli layanan tetap dikelola dari CRUD Services yang sudah berjalan.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            ['name' => 'Cuci Reguler', 'desc' => 'Cocok untuk laundry kiloan harian.', 'time' => '2-3 hari'],
                            ['name' => 'Cuci Setrika', 'desc' => 'Pakaian bersih, rapi, siap pakai.', 'time' => '2 hari'],
                            ['name' => 'Express Laundry', 'desc' => 'Prioritas untuk kebutuhan cepat.', 'time' => '1 hari'],
                            ['name' => 'Pickup Service', 'desc' => 'Pelanggan bisa pilih penjemputan.', 'time' => 'Terjadwal'],
                        ] as $service)
                            <article class="vault-card hover-lift p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-black text-neutral-950">{{ $service['name'] }}</h3>
                                        <p class="mt-3 text-sm leading-6 text-neutral-600">{{ $service['desc'] }}</p>
                                    </div>
                                    <span class="rounded-full bg-[#fff3ea] px-3 py-1 text-xs font-bold text-[#d94b12]">{{ $service['time'] }}</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="relative z-10 vault-section pb-24">
                <div class="overflow-hidden rounded-[2rem] bg-[#FF6626] p-8 text-white shadow-2xl shadow-orange-500/25 sm:p-12 lg:p-16">
                    <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                        <div>
                            <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-white/70">Ready to wash smarter</p>
                            <h2 class="mt-4 font-display text-5xl leading-tight sm:text-6xl">Mulai kelola laundry dengan VAULTLAUNDRY.</h2>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                            @auth
                                <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-black text-neutral-950 transition hover:bg-neutral-950 hover:text-white">
                                    Buka Dashboard
                                </a>
                            @else
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-black text-neutral-950 transition hover:bg-neutral-950 hover:text-white">
                                        Register
                                    </a>
                                @endif
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-white/40 px-6 py-3 text-sm font-black text-white transition hover:bg-white hover:text-neutral-950">
                                        Login
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </section>

            <footer class="relative z-10 border-t border-black/10 bg-[#FAF4EA]/80 py-10">
                <div class="vault-section flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('logo.svg') }}" alt="VAULTLAUNDRY" class="h-10 w-10 rounded-2xl">
                        <div>
                            <p class="text-sm font-black tracking-[0.18em]">VAULTLAUNDRY</p>
                            <p class="text-sm text-neutral-500">Laundry booking, monitoring, payment, invoice, report.</p>
                        </div>
                    </div>
                    <p class="text-sm text-neutral-500">&copy; {{ date('Y') }} VAULTLAUNDRY. Built with Laravel Blade.</p>
                </div>
            </footer>
        </main>
    </body>
</html>
