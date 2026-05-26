<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl border border-transparent bg-[#FF6626] px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-orange-500/20 transition duration-200 hover:-translate-y-0.5 hover:bg-[#d94b12] hover:shadow-md hover:shadow-orange-500/25 focus:outline-none focus:ring-2 focus:ring-[#FF6626] focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50']) }}>
    {{ $slot }}
</button>
