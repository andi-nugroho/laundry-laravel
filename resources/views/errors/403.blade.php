@php
    $user = auth()->user();
    $dashboardRoute = 'login';

    if ($user) {
        $dashboardRoute = match ($user->role) {
            \App\Models\User::ROLE_ADMIN => 'dashboard.admin',
            \App\Models\User::ROLE_KASIR => 'dashboard.kasir',
            default => 'dashboard.user',
        };
    }

    $dashboardUrl = route($dashboardRoute);
    $backUrl = url()->previous() !== url()->current() ? url()->previous() : $dashboardUrl;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <title>Akses Tidak Diizinkan - VAULTLAUNDRY</title>
        <link rel="icon" href="{{ asset('favicon.ico') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#FAF4EA] font-sans text-neutral-950 antialiased">
        <main class="flex min-h-screen items-center justify-center px-6 py-10">
            <section class="w-full max-w-2xl overflow-hidden rounded-[2rem] border border-[#E8DCCB] bg-[#FFF9F1] shadow-[0_24px_70px_rgba(24,21,18,0.12)]">
                <div class="border-b border-[#E8DCCB] bg-[#FBF3E7] px-6 py-5">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('logo.svg') }}" alt="VAULTLAUNDRY" class="h-11 w-11 rounded-2xl">
                        <div>
                            <div class="text-lg font-black tracking-[0.16em] text-neutral-950">VAULTLAUNDRY</div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#FF6626]">403 Forbidden</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-10 text-center sm:px-10">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-[2rem] border border-orange-200 bg-orange-50 text-[#FF6626] shadow-lg shadow-orange-500/15">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15.5v.01M7.75 10.25V8a4.25 4.25 0 0 1 8.5 0v2.25m-10 0h11.5A1.75 1.75 0 0 1 19.5 12v6.25A1.75 1.75 0 0 1 17.75 20H6.25a1.75 1.75 0 0 1-1.75-1.75V12a1.75 1.75 0 0 1 1.75-1.75Z" />
                        </svg>
                    </div>

                    <h1 class="mt-7 text-3xl font-black tracking-tight text-neutral-950 sm:text-4xl">
                        Akses Tidak Diizinkan
                    </h1>
                    <p class="mx-auto mt-3 max-w-md text-sm font-semibold leading-6 text-neutral-500">
                        Anda tidak memiliki izin untuk membuka halaman ini.
                    </p>

                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                        <a href="{{ $backUrl }}" class="inline-flex items-center justify-center rounded-2xl border border-[#E8DCCB] bg-[#FFF9F1] px-5 py-3 text-sm font-black text-neutral-800 transition-colors duration-200 hover:border-[#FF6626]/40 hover:text-[#FF6626]">
                            Kembali
                        </a>
                        <a href="{{ $dashboardUrl }}" class="inline-flex items-center justify-center rounded-2xl bg-[#FF6626] px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/25 transition-colors duration-200 hover:bg-[#d94b12]">
                            Ke Dashboard
                        </a>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
