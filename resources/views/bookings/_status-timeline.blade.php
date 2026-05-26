@php
    $flowStatuses = [
        'booking_masuk',
        'diterima',
        'dicuci',
        'dikeringkan',
        'disetrika',
        'selesai',
        'diambil',
    ];
    $currentIndex = array_search($booking->status, $flowStatuses, true);
    $isCanceled = $booking->status === 'dibatalkan';
@endphp

<div class="rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] shadow-[0_14px_34px_rgba(24,21,18,0.06)]">
    <div class="p-4 sm:p-6">
        <h3 class="text-base font-black text-neutral-900">Monitoring Status</h3>
        <div class="mt-6 overflow-x-auto">
            <ol class="flex min-w-max items-start">
                @foreach ($flowStatuses as $index => $status)
                    @php
                        $isDone = ! $isCanceled && $currentIndex !== false && $index <= $currentIndex;
                    @endphp
                    <li class="flex items-start {{ $loop->last ? '' : 'w-40' }}">
                        <div class="flex flex-col items-center">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-black {{ $isDone ? 'bg-[#FF6626] text-white' : 'bg-[#FBF3E7] text-neutral-400 ring-1 ring-[#E8DCCB]' }}">
                                {{ $index + 1 }}
                            </span>
                            <span class="mt-2 w-28 text-center text-xs font-bold {{ $isDone ? 'text-neutral-900' : 'text-neutral-500' }}">
                                {{ str_replace('_', ' ', ucfirst($status)) }}
                            </span>
                        </div>
                        @if (! $loop->last)
                            <div class="mt-4 h-0.5 flex-1 {{ $isDone && $index < $currentIndex ? 'bg-[#FF6626]' : 'bg-[#E8DCCB]' }}"></div>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>

        @if ($isCanceled)
            <div class="mt-6 rounded-2xl bg-red-50 p-4 text-sm font-semibold text-red-700 ring-1 ring-red-100">
                Booking ini dibatalkan.
            </div>
        @endif
    </div>
</div>
