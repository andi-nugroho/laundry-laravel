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

        @vite(['resources/css/public.css', 'resources/js/public.js'])
    </head>
    <body class="vault-public bg-[#FAF4EA] font-sans text-neutral-950 antialiased">
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
                        <a href="#faq" class="transition hover:text-[#FF6626]">FAQ</a>
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

            <section class="relative z-10 vault-section flex min-h-[calc(100vh-72px)] items-center pt-28 pb-12 sm:pt-[7.25rem] lg:pt-32 lg:pb-20">
                <div class="grid w-full items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-12">
                    <div class="max-w-3xl">
                        <p class="reveal font-mono-vault mb-6 inline-flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.28em] text-[#FF6626]">
                            <span class="h-px w-10 bg-[#FF6626]"></span>
                            Laundry system for modern teams
                        </p>
                        <h1 class="reveal reveal-delay-100 font-display text-6xl leading-none tracking-normal text-neutral-950 sm:text-7xl lg:text-8xl">
                            Booking laundry jadi rapi dari masuk sampai lunas.
                        </h1>
                        <p class="reveal reveal-delay-200 mt-8 max-w-2xl text-lg leading-8 text-neutral-600 sm:text-xl">
                            VAULTLAUNDRY menyatukan data pelanggan, booking, monitoring status cucian, transaksi pembayaran, invoice, dan laporan pendapatan dalam satu sistem yang siap dipakai.
                        </p>

                        <div class="reveal reveal-delay-300 mt-10 flex flex-col gap-3 sm:flex-row">
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

                        <div class="reveal reveal-delay-400 mt-12 grid max-w-2xl grid-cols-3 gap-4 border-y border-black/10 py-6">
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

                    <div class="reveal-fade reveal-delay-200 vault-hero-visual" aria-hidden="true">
                        <div class="vault-hero-glow"></div>
                        <div class="vault-hero-glow-secondary"></div>

                        <img
                            src="{{ asset('assets/washing-machine.webp') }}"
                            alt="Mesin cuci VAULTLAUNDRY"
                            class="vault-hero-main"
                            width="480"
                            height="480"
                        >

                        <div class="vault-hero-live-card">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#FF6626]">Live Operation</p>
                            <p class="mt-1 text-sm font-black text-neutral-900">LDY-2026-0001</p>
                            <div class="mt-2 flex items-center gap-2 text-xs font-semibold text-neutral-500">
                                <span class="relative flex h-2 w-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#FF6626] opacity-75"></span>
                                    <span class="relative inline-flex h-2 w-2 rounded-full bg-[#FF6626]"></span>
                                </span>
                                Proses Dicuci
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="fitur" class="relative z-10 vault-section py-24">
                <div class="reveal max-w-3xl">
                    <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-[#FF6626]">Core system</p>
                    <h2 class="mt-4 font-display text-5xl leading-tight text-neutral-950 sm:text-6xl">Fitur utama yang menjaga operasional tetap terkendali.</h2>
                </div>

                <div class="relative mt-12 grid gap-5 md:grid-cols-2 xl:grid-cols-5">
                    <div class="absolute top-12 left-[10%] right-[10%] h-[2px] bg-gradient-to-r from-transparent via-[#FF6626]/30 to-transparent hidden xl:block"></div>
                    
                    @foreach ([
                        ['icon' => 'calendar.webp', 'title' => 'Booking', 'text' => 'Catat pelanggan, layanan, berat, tanggal masuk, estimasi selesai, dan harga.'],
                        ['icon' => 'monitor-graph.webp', 'title' => 'Monitoring', 'text' => 'Pantau status dari booking_masuk sampai diambil atau dibatalkan.'],
                        ['icon' => 'wallet.webp', 'title' => 'Pembayaran', 'text' => 'Proses cash, transfer, ewallet, partial, paid, dan hitung kembalian.'],
                        ['icon' => 'receipt.webp', 'title' => 'Cetak Nota', 'text' => 'Invoice rapi siap print dengan rincian booking, layanan, dan kasir.'],
                        ['icon' => 'chartpie.webp', 'title' => 'Laporan', 'text' => 'Filter transaksi dan ringkasan pendapatan untuk admin dan kasir.'],
                    ] as $feature)
                        <article class="reveal reveal-delay-{{ $loop->index * 100 }} vault-card relative z-10 bg-white p-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(255,102,38,0.15)] hover:border-[#FF6626]/30">
                            <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FF6626] text-white shadow-lg shadow-[#FF6626]/30">
                                <img src="{{ asset('assets/'.$feature['icon']) }}" alt="{{ $feature['title'] }}" class="h-8 w-8 object-contain">
                            </div>
                            <h3 class="text-lg font-black text-neutral-950">{{ $feature['title'] }}</h3>
                            <p class="mt-3 text-sm leading-6 text-neutral-600">{{ $feature['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section id="alur" class="relative z-10 bg-neutral-950 py-24 text-white">
                <div class="vault-section">
                    <div class="reveal grid gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:items-end">
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
                            <div class="reveal reveal-delay-{{ $loop->index * 100 }} rounded-[1.5rem] border border-white/10 bg-white/[0.04] p-6">
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
                    <div class="reveal">
                        <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-[#FF6626]">Laundry services</p>
                        <h2 class="mt-4 font-display text-5xl leading-tight text-neutral-950 sm:text-6xl">Layanan yang umum dipakai pelanggan harian.</h2>
                        <p class="mt-6 text-lg leading-8 text-neutral-600">
                            Section ini disiapkan sebagai etalase layanan. Data asli layanan tetap dikelola dari CRUD Services yang sudah berjalan.
                        </p>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        @foreach ([
                            ['icon' => 'washing-machine.webp', 'name' => 'Cuci Reguler', 'desc' => 'Cocok untuk laundry kiloan harian.', 'time' => '2-3 hari'],
                            ['icon' => 'iron.webp', 'name' => 'Cuci Setrika', 'desc' => 'Pakaian bersih, rapi, siap pakai.', 'time' => '2 hari'],
                            ['icon' => 'detergent.webp', 'name' => 'Express Laundry', 'desc' => 'Prioritas untuk kebutuhan cepat.', 'time' => '1 hari'],
                            ['icon' => 'delivery-scooter.webp', 'name' => 'Pickup Service', 'desc' => 'Pelanggan bisa pilih penjemputan.', 'time' => 'Terjadwal'],
                        ] as $service)
                            <article class="reveal reveal-delay-{{ $loop->index * 100 }} group relative overflow-hidden rounded-3xl border border-black/5 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#FF6626]/10 hover:border-[#FF6626]/20">
                                <div class="flex items-start gap-5">
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-[#FFF9F1] border border-[#FF6626]/10 transition-transform group-hover:scale-110">
                                        <img src="{{ asset('assets/'.$service['icon']) }}" alt="{{ $service['name'] }}" class="h-10 w-10 object-contain drop-shadow-sm">
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex items-start justify-between gap-2">
                                            <h3 class="text-xl font-black text-neutral-950">{{ $service['name'] }}</h3>
                                            <span class="inline-flex shrink-0 items-center rounded-full bg-[#fff3ea] px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-[#d94b12]">{{ $service['time'] }}</span>
                                        </div>
                                        <div class="mt-3 w-8 border-b-2 border-[#FF6626]/30"></div>
                                        <p class="mt-3 text-sm leading-relaxed text-neutral-600">{{ $service['desc'] }}</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="faq" class="relative z-10 bg-[#0f0e0c] py-24 text-white">
                <div class="vault-section max-w-4xl mx-auto">
                    <div class="reveal text-center mb-16">
                        <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-[#FF6626]">Pertanyaan Umum</p>
                        <h2 class="mt-4 font-display text-4xl leading-tight sm:text-5xl text-white">Yang sering ditanyakan.</h2>
                    </div>

                    <div class="space-y-4" x-data="{ active: null }">
                        @foreach([
                            ['q' => 'Apa itu VAULTLAUNDRY?', 'a' => 'VAULTLAUNDRY adalah sistem manajemen operasional laundry modern yang mencakup proses booking, monitoring status, pembayaran, pencetakan nota, hingga rekapitulasi pendapatan harian.'],
                            ['q' => 'Bagaimana cara booking laundry?', 'a' => 'Kasir atau Admin dapat membuat booking dari dashboard, memasukkan data pelanggan, memilih layanan, dan menentukan berat cucian. Sistem akan menghitung harga dan estimasi selesai secara otomatis.'],
                            ['q' => 'Apakah bisa melihat status cucian?', 'a' => 'Tentu. Pelanggan (User) yang login dapat memantau progres cucian mereka dari dashboard secara real-time, mulai dari diterima, dicuci, disetrika, hingga selesai.'],
                            ['q' => 'Metode pembayaran apa saja yang tersedia?', 'a' => 'Sistem mencatat pembayaran secara tunai (Cash), Transfer Bank, dan E-Wallet (termasuk QRIS). Admin juga bisa memproses pembayaran parsial atau lunas penuh.'],
                            ['q' => 'Apakah nota bisa diunduh?', 'a' => 'Ya. Setelah transaksi dibuat, nota berformat PDF bisa langsung diunduh atau dicetak dari menu detail booking maupun riwayat pembayaran.'],
                        ] as $index => $faq)
                            <div class="reveal reveal-delay-{{ $loop->index * 100 }} bg-[#1a1714] border border-[#2a2520] rounded-2xl transition-all duration-300 hover:border-[#FF6626]/30 overflow-hidden">
                                <button @click="active !== {{ $index }} ? active = {{ $index }} : active = null" class="flex w-full items-center justify-between py-5 px-6 text-left transition hover:text-[#FF6626] text-white">
                                    <span class="text-base sm:text-lg font-bold text-white pr-4">{{ $faq['q'] }}</span>
                                    <span class="ml-6 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/5 transition-colors" :class="active === {{ $index }} ? 'bg-[#FF6626] text-white' : ''">
                                        <svg x-show="active !== {{ $index }}" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                        <svg x-show="active === {{ $index }}" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                                    </span>
                                </button>
                                <div x-show="active === {{ $index }}" x-transition x-cloak>
                                    <div class="px-6 pb-6 text-gray-300 leading-relaxed text-sm sm:text-base border-t border-[#2a2520] pt-4">
                                        {{ $faq['a'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="relative z-10 vault-section py-24">
                <div class="reveal overflow-hidden rounded-[2rem] bg-gradient-to-r from-[#FF6626] to-[#f2520f] p-8 text-white shadow-2xl shadow-orange-500/30 sm:p-12 lg:p-16">
                    <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center relative z-10">
                        <div>
                            <p class="font-mono-vault text-xs font-black uppercase tracking-[0.28em] text-white">Ready to wash smarter</p>
                            <h2 class="mt-4 font-display text-5xl leading-tight sm:text-6xl text-white font-extrabold">Mulai kelola laundry dengan VAULTLAUNDRY.</h2>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row lg:flex-col shrink-0">
                            @auth
                                <a href="{{ route(auth()->user()->dashboardRouteName()) }}" class="inline-flex items-center justify-center rounded-full bg-neutral-950 px-8 py-4 text-sm font-black text-white transition hover:bg-white hover:text-neutral-950 shadow-md">
                                    Buka Dashboard
                                </a>
                            @else
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-neutral-950 px-8 py-4 text-sm font-black text-white transition hover:bg-white hover:text-neutral-950 shadow-md">
                                        Daftar Sekarang
                                    </a>
                                @endif
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border-2 border-white bg-transparent px-8 py-4 text-sm font-black text-white transition hover:bg-white hover:text-neutral-950">
                                        Login Portal
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </section>

            <footer class="relative z-10 bg-[#0a0a0a] pt-20 pb-8 text-white overflow-hidden">
                <div class="vault-section relative z-10">
                    <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4 lg:gap-8 mb-20">
                        <div class="lg:col-span-2">
                            <div class="flex items-center gap-3 mb-6">
                                <img src="{{ asset('logo.svg') }}" alt="VAULTLAUNDRY" class="h-10 w-10 rounded-xl">
                                <span class="text-sm font-black tracking-[0.18em] text-white">VAULTLAUNDRY</span>
                            </div>
                            <p class="text-white/70 max-w-sm leading-relaxed text-sm">
                                Solusi manajemen laundry cerdas, dari kasir hingga laporan bulanan. Cepat, transparan, dan terpercaya.
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-bold uppercase tracking-widest text-[#FF6626] mb-6">Menu Navigasi</h4>
                            <ul class="space-y-4 text-sm font-medium text-white/70">
                                <li><a href="#fitur" class="hover:text-white transition">Fitur Unggulan</a></li>
                                <li><a href="#alur" class="hover:text-white transition">Alur Operasional</a></li>
                                <li><a href="#layanan" class="hover:text-white transition">Daftar Layanan</a></li>
                                <li><a href="#faq" class="hover:text-white transition">Tanya Jawab (FAQ)</a></li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-bold uppercase tracking-widest text-[#FF6626] mb-6">Akses Sistem</h4>
                            <ul class="space-y-4 text-sm font-medium text-white/70">
                                @if (Route::has('login'))
                                    <li><a href="{{ route('login') }}" class="hover:text-white transition">Login Portal</a></li>
                                @endif
                                @if (Route::has('register'))
                                    <li><a href="{{ route('register') }}" class="hover:text-white transition">Pendaftaran Akun</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    <div class="relative z-10 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 md:flex-row">
                        <p class="text-sm text-white/50">&copy; {{ date('Y') }} VAULTLAUNDRY. All rights reserved.</p>
                        <p class="text-sm text-white/50">
                            Open Source | 2026 Created by
                            <a
                                href="https://andidelouise.net"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="ml-1 font-bold text-white underline decoration-[#FF6626] decoration-2 underline-offset-4 transition hover:text-[#FF6626]"
                            >Andi Nugroho</a>
                        </p>
                    </div>
                </div>

                <!-- Outline Decorative Text -->
                <div class="absolute -bottom-8 lg:-bottom-16 left-0 right-0 pointer-events-none select-none flex justify-center w-full overflow-hidden whitespace-nowrap opacity-40 z-0">
                    <span class="font-display text-[16vw] leading-none font-black text-transparent select-none" style="-webkit-text-stroke: 2.5px rgba(255, 102, 38, 0.25);">
                        LAUNDRY
                    </span>
                </div>
            </footer>
        </main>
    </body>
</html>
