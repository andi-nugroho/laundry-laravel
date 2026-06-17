@php
    $waText = rawurlencode('Halo VAULTLAUNDRY, saya ingin bertanya tentang layanan laundry.');
    $waUrl = 'https://wa.me/6285316065960?text='.$waText;
@endphp

<div
    x-data="{
        minimized: false,
        visible: false,
        init() {
            const updateVisibility = () => {
                const hero = document.getElementById('hero');
                const heroEnd = hero
                    ? hero.getBoundingClientRect().top + window.scrollY + hero.offsetHeight
                    : 0;
                const threshold = Math.max(heroEnd * 0.72, 350);

                this.visible = window.scrollY >= threshold;
            };

            updateVisibility();
            window.addEventListener('scroll', updateVisibility, { passive: true });
            window.addEventListener('resize', updateVisibility, { passive: true });
        },
    }"
    x-cloak
    x-show="visible"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-6 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
    class="pointer-events-none fixed bottom-4 right-3 z-40 sm:bottom-6 sm:right-6"
    aria-live="polite"
>
    <div class="pointer-events-auto">
        <template x-if="! minimized">
            <div class="relative">
                <button
                    type="button"
                    @click="minimized = true"
                    class="absolute -right-1 top-0 z-20 flex h-7 w-7 items-center justify-center rounded-full bg-neutral-950 text-base font-black leading-none text-white shadow-lg transition hover:scale-105 hover:bg-neutral-800 sm:-right-2 sm:-top-1 sm:h-8 sm:w-8"
                    aria-label="Minimalkan asisten"
                >
                    &minus;
                </button>

                <img
                    src="{{ asset('assets/cewe-laundry.webp') }}"
                    alt="Asisten VAULTLAUNDRY"
                    width="180"
                    height="240"
                    class="h-36 w-auto object-contain drop-shadow-[0_16px_32px_rgba(24,21,18,0.22)] sm:h-48 lg:h-52"
                    loading="lazy"
                    decoding="async"
                >
            </div>
        </template>

        <template x-if="minimized">
            <div class="flex flex-col items-end gap-2.5 sm:gap-3">
                <div class="max-w-[11rem] rounded-2xl rounded-br-md border border-[#E8DCCB] bg-[#FFF9F1] px-3.5 py-2.5 text-xs font-bold leading-snug text-neutral-800 shadow-lg shadow-neutral-950/10 sm:max-w-[12.5rem] sm:px-4 sm:py-3 sm:text-sm">
                    Butuh bantuan laundry?
                </div>

                <a
                    href="{{ $waUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group flex h-16 w-16 items-center justify-center rounded-full bg-[#25D366] shadow-xl shadow-green-500/30 transition duration-300 hover:scale-105 hover:bg-[#1fb85a] sm:h-[4.5rem] sm:w-[4.5rem]"
                    aria-label="Hubungi VAULTLAUNDRY via WhatsApp"
                >
                    <img
                        src="{{ asset('assets/whatsapp.gif') }}"
                        alt=""
                        width="52"
                        height="52"
                        class="h-11 w-11 rounded-full object-cover sm:h-12 sm:w-12"
                        loading="lazy"
                        decoding="async"
                    >
                </a>
            </div>
        </template>
    </div>
</div>
