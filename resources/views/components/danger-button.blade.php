<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-red-500/15 transition duration-200 hover:-translate-y-0.5 hover:bg-red-700 hover:shadow-md hover:shadow-red-500/20 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50']) }}>
    {{ $slot }}
</button>
