<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-red-500/15 transition-colors duration-200 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50']) }}>
    {{ $slot }}
</button>
