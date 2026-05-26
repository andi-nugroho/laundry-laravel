@props([
    'storageKey' => 'vaultListView',
    'title' => 'Data',
    'description' => null,
])

<div
    x-data="{
        viewMode: localStorage.getItem('{{ $storageKey }}') || (window.innerWidth < 768 ? 'card' : 'table'),
        setView(mode) {
            this.viewMode = mode;
            localStorage.setItem('{{ $storageKey }}', mode);
        },
    }"
    class="vault-list-panel overflow-hidden rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] shadow-[0_18px_45px_rgba(24,21,18,0.08)]"
>
    <div class="flex flex-col gap-4 border-b border-[#E8DCCB] bg-[#FBF3E7]/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
            <h3 class="text-sm font-black uppercase tracking-[0.12em] text-neutral-800">{{ $title }}</h3>
            @if ($description)
                <p class="mt-1 text-sm font-medium text-neutral-500">{{ $description }}</p>
            @endif
        </div>

        <div class="inline-flex w-full rounded-2xl border border-[#E8DCCB] bg-[#FFF9F1] p-1 shadow-sm sm:w-auto">
            <button
                type="button"
                class="flex-1 rounded-xl px-3 py-2 text-xs font-black transition sm:flex-none"
                :class="viewMode === 'table' ? 'bg-[#FF6626] text-white shadow-sm shadow-orange-500/20' : 'text-neutral-500 hover:text-[#FF6626]'"
                @click="setView('table')"
            >
                Table
            </button>
            <button
                type="button"
                class="flex-1 rounded-xl px-3 py-2 text-xs font-black transition sm:flex-none"
                :class="viewMode === 'card' ? 'bg-[#FF6626] text-white shadow-sm shadow-orange-500/20' : 'text-neutral-500 hover:text-[#FF6626]'"
                @click="setView('card')"
            >
                Card
            </button>
        </div>
    </div>

    <div x-show="viewMode === 'table'">
        {{ $table ?? '' }}
    </div>

    <div x-show="viewMode === 'card'" style="display: none;">
        {{ $cards ?? '' }}
    </div>

    @isset($footer)
        <div class="border-t border-[#E8DCCB] px-5 py-4 sm:px-6">
            {{ $footer }}
        </div>
    @endisset
</div>
