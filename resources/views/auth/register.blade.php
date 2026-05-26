<x-guest-layout>
    <div class="mb-8">
        <p class="font-mono-vault text-xs font-bold uppercase tracking-[0.28em] text-[#FF6626]">Create account</p>
        <h1 class="mt-3 font-display text-5xl leading-none text-neutral-950">Daftar VAULTLAUNDRY.</h1>
        <p class="mt-4 text-sm leading-6 text-neutral-600">
            Buat akun pelanggan untuk mulai booking laundry dan memantau status cucian Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-bold text-neutral-800">{{ __('Name') }}</label>
            <input id="name" class="mt-2 block w-full rounded-2xl border-black/10 bg-[#FAF4EA] px-4 py-3 text-sm text-neutral-950 shadow-sm transition placeholder:text-neutral-400 focus:border-[#FF6626] focus:ring-[#FF6626]" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama lengkap">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-neutral-800">{{ __('Email') }}</label>
            <input id="email" class="mt-2 block w-full rounded-2xl border-black/10 bg-[#FAF4EA] px-4 py-3 text-sm text-neutral-950 shadow-sm transition placeholder:text-neutral-400 focus:border-[#FF6626] focus:ring-[#FF6626]" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="password" class="block text-sm font-bold text-neutral-800">{{ __('Password') }}</label>
                <input id="password" class="mt-2 block w-full rounded-2xl border-black/10 bg-[#FAF4EA] px-4 py-3 text-sm text-neutral-950 shadow-sm transition placeholder:text-neutral-400 focus:border-[#FF6626] focus:ring-[#FF6626]" type="password" name="password" required autocomplete="new-password" placeholder="********">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-neutral-800">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" class="mt-2 block w-full rounded-2xl border-black/10 bg-[#FAF4EA] px-4 py-3 text-sm text-neutral-950 shadow-sm transition placeholder:text-neutral-400 focus:border-[#FF6626] focus:ring-[#FF6626]" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="********">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <button type="submit" class="vault-button w-full">
            {{ __('Register') }}
        </button>

        <p class="text-center text-sm text-neutral-600">
            Sudah punya akun?
            <a class="font-bold text-[#FF6626] transition hover:text-[#d94b12]" href="{{ route('login') }}">
                {{ __('Log in') }}
            </a>
        </p>
    </form>
</x-guest-layout>
