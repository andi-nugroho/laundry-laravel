<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-xl border border-[#E8DCCB] bg-[#FFF9F1] px-4 py-2.5 text-sm font-semibold text-neutral-800 shadow-sm shadow-neutral-950/5 transition-colors duration-200 hover:border-[#FF6626]/40 hover:text-[#FF6626] focus:outline-none focus:ring-2 focus:ring-[#FF6626] focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50']) }}>
    {{ $slot }}
</button>
