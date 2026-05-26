<x-guest-layout>
    <div class="mb-8">
        <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-[#FF6626]">Welcome back</p>
        <h1 class="mt-3 font-display text-5xl leading-none text-neutral-950">Masuk ke akun Anda.</h1>
        <p class="mt-4 text-sm leading-6 text-neutral-600">
            Kelola booking, status laundry, pembayaran, dan laporan VAULTLAUNDRY dari satu dashboard.
        </p>
    </div>

    <x-auth-session-status class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-neutral-800">{{ __('Email') }}</label>
            <input id="email" class="mt-2 block w-full rounded-2xl border-black/10 bg-[#FAF4EA] px-4 py-3 text-sm text-neutral-950 shadow-sm transition placeholder:text-neutral-400 focus:border-[#FF6626] focus:ring-[#FF6626]" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between gap-4">
                <label for="password" class="block text-sm font-bold text-neutral-800">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-[#FF6626] transition hover:text-[#d94b12]" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
            <input id="password" class="mt-2 block w-full rounded-2xl border-black/10 bg-[#FAF4EA] px-4 py-3 text-sm text-neutral-950 shadow-sm transition placeholder:text-neutral-400 focus:border-[#FF6626] focus:ring-[#FF6626]" type="password" name="password" required autocomplete="current-password" placeholder="********">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="inline-flex items-center gap-2">
            <input id="remember_me" type="checkbox" class="rounded border-black/20 bg-[#FAF4EA] text-[#FF6626] shadow-sm focus:ring-[#FF6626]" name="remember">
            <span class="text-sm font-medium text-neutral-600">{{ __('Remember me') }}</span>
        </label>

        <button type="submit" class="vault-button w-full">
            {{ __('Log in') }}
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-neutral-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold text-[#FF6626] transition hover:text-[#d94b12]">
                    {{ __('Register') }}
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>
