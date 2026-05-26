<x-guest-layout>
    <div class="mb-8">
        <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-[#FF6626]">Reset access</p>
        <h1 class="mt-3 font-display text-5xl leading-none text-neutral-950">Lupa password?</h1>
        <p class="mt-4 text-sm leading-6 text-neutral-600">
            Masukkan email akun Anda. Kami akan mengirim link reset password agar Anda bisa masuk kembali ke VAULTLAUNDRY.
        </p>
    </div>

    <x-auth-session-status class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-neutral-800">{{ __('Email') }}</label>
            <input id="email" class="mt-2 block w-full rounded-2xl border-black/10 bg-[#FAF4EA] px-4 py-3 text-sm text-neutral-950 shadow-sm transition placeholder:text-neutral-400 focus:border-[#FF6626] focus:ring-[#FF6626]" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="vault-button w-full">
            {{ __('Email Password Reset Link') }}
        </button>

        <p class="text-center text-sm text-neutral-600">
            Ingat password?
            <a class="font-bold text-[#FF6626] transition hover:text-[#d94b12]" href="{{ route('login') }}">
                {{ __('Log in') }}
            </a>
        </p>
    </form>
</x-guest-layout>
