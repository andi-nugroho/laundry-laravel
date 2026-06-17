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
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root {
                --vault-bg: #FAF4EA;
                --vault-card: #FFF9F1;
                --vault-panel: #FBF3E7;
                --vault-border: #E8DCCB;
                --vault-orange: #FF6626;
                --vault-text: #171411;
                --vault-muted: #706A63;
            }

            * {
                box-sizing: border-box;
            }

            body {
                min-height: 100vh;
                margin: 0;
                background: var(--vault-bg);
                color: var(--vault-text);
                font-family: "DM Sans", "Instrument Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            .page {
                display: flex;
                min-height: 100vh;
                align-items: center;
                justify-content: center;
                padding: 2.5rem 1.5rem;
            }

            .card {
                width: 100%;
                max-width: 42rem;
                overflow: hidden;
                border: 1px solid var(--vault-border);
                border-radius: 2rem;
                background: var(--vault-card);
                box-shadow: 0 24px 70px rgba(24, 21, 18, 0.12);
            }

            .topbar {
                border-bottom: 1px solid var(--vault-border);
                background: var(--vault-panel);
                padding: 1.25rem 1.5rem;
            }

            .brand {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .brand img {
                width: 2.75rem;
                height: 2.75rem;
                border-radius: 1rem;
            }

            .brand-name {
                font-size: 1.1rem;
                font-weight: 900;
                letter-spacing: 0.16em;
            }

            .eyebrow {
                margin-top: 0.2rem;
                color: var(--vault-orange);
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.18em;
                text-transform: uppercase;
            }

            .content {
                padding: 2.5rem 1.5rem;
                text-align: center;
            }

            .lock {
                display: inline-flex;
                width: 6rem;
                height: 6rem;
                align-items: center;
                justify-content: center;
                border: 1px solid #fed7aa;
                border-radius: 2rem;
                background: #fff7ed;
                color: var(--vault-orange);
                box-shadow: 0 16px 34px rgba(255, 102, 38, 0.15);
            }

            .lock svg {
                width: 3rem;
                height: 3rem;
            }

            h1 {
                margin: 1.75rem 0 0;
                font-size: clamp(2rem, 6vw, 2.65rem);
                line-height: 1.05;
                letter-spacing: -0.02em;
            }

            p {
                max-width: 28rem;
                margin: 0.8rem auto 0;
                color: var(--vault-muted);
                font-size: 0.95rem;
                font-weight: 650;
                line-height: 1.65;
            }

            .actions {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                justify-content: center;
                margin-top: 2rem;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 3rem;
                border-radius: 1rem;
                padding: 0.8rem 1.25rem;
                font-size: 0.9rem;
                font-weight: 900;
                text-decoration: none;
                transition: transform 180ms ease, border-color 180ms ease, background-color 180ms ease, color 180ms ease;
            }

            .btn:hover {
                transform: translateY(-1px);
            }

            .btn-secondary {
                border: 1px solid var(--vault-border);
                background: var(--vault-card);
                color: var(--vault-text);
            }

            .btn-secondary:hover {
                border-color: rgba(255, 102, 38, 0.45);
                color: var(--vault-orange);
            }

            .btn-primary {
                border: 1px solid var(--vault-orange);
                background: var(--vault-orange);
                color: #fff;
                box-shadow: 0 14px 28px rgba(255, 102, 38, 0.22);
            }

            .btn-primary:hover {
                background: #d94b12;
            }

            @media (min-width: 640px) {
                .content {
                    padding: 2.75rem 2.5rem;
                }

                .actions {
                    flex-direction: row;
                }
            }
        </style>
    </head>
    <body>
        <main class="page">
            <section class="card">
                <div class="topbar">
                    <div class="brand">
                        <img src="{{ asset('logo.svg') }}" alt="VAULTLAUNDRY">
                        <div>
                            <div class="brand-name">VAULTLAUNDRY</div>
                            <div class="eyebrow">403 Forbidden</div>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <div class="lock">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15.5v.01M7.75 10.25V8a4.25 4.25 0 0 1 8.5 0v2.25m-10 0h11.5A1.75 1.75 0 0 1 19.5 12v6.25A1.75 1.75 0 0 1 17.75 20H6.25a1.75 1.75 0 0 1-1.75-1.75V12a1.75 1.75 0 0 1 1.75-1.75Z" />
                        </svg>
                    </div>

                    <h1>Akses Tidak Diizinkan</h1>
                    <p>
                        Anda tidak memiliki izin untuk membuka halaman ini.
                    </p>

                    <div class="actions">
                        <a href="{{ $backUrl }}" class="btn btn-secondary">
                            Kembali
                        </a>
                        <a href="{{ $dashboardUrl }}" class="btn btn-primary">
                            Ke Dashboard
                        </a>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
