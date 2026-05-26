<div class="flex flex-wrap items-center gap-3 rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] px-5 py-4 text-sm font-bold text-neutral-600 shadow-[0_14px_34px_rgba(24,21,18,0.06)]">
    <span class="flex items-center gap-2">
        <span class="h-2.5 w-2.5 rounded-full" :class="connected ? 'bg-green-500' : 'bg-amber-500'"></span>
        <span x-text="status"></span>
    </span>
    <span class="hidden text-neutral-400 sm:inline">|</span>
    <span>Terakhir update: <span class="text-neutral-900" x-text="lastUpdated"></span></span>
    <button
        type="button"
        @click="refreshStats(true)"
        class="inline-flex items-center rounded-full border border-[#E8DCCB] bg-white px-3 py-1.5 text-xs font-bold text-neutral-700 transition hover:border-[#FF6626]/40 hover:text-[#FF6626] sm:ml-auto"
    >
        Refresh Data
    </button>
</div>
