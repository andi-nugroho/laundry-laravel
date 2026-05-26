@php
    $flowStatuses = [
        'booking_masuk'  => ['label' => 'Booking Masuk',  'icon' => '📋', 'desc' => 'Pesanan diterima sistem'],
        'diterima'       => ['label' => 'Diterima',       'icon' => '✅', 'desc' => 'Laundry diterima petugas'],
        'dicuci'         => ['label' => 'Dicuci',         'icon' => '🫧', 'desc' => 'Proses pencucian'],
        'dikeringkan'    => ['label' => 'Dikeringkan',    'icon' => '💨', 'desc' => 'Proses pengeringan'],
        'disetrika'      => ['label' => 'Disetrika',      'icon' => '👔', 'desc' => 'Proses setrika & lipat'],
        'selesai'        => ['label' => 'Selesai',        'icon' => '📦', 'desc' => 'Siap diambil'],
        'diambil'        => ['label' => 'Diambil',        'icon' => '🏠', 'desc' => 'Sudah diambil pelanggan'],
    ];
    $statusKeys = array_keys($flowStatuses);
    $currentIndex = array_search($booking->status, $statusKeys, true);
    $isCanceled = $booking->status === 'dibatalkan';
@endphp

<div class="vault-tracking-timeline">
    <h4 class="text-xs font-black uppercase tracking-[0.14em] text-neutral-400 mb-4">Tracking Status</h4>

    @if ($isCanceled)
        <div class="rounded-2xl bg-red-50 p-4 text-sm font-semibold text-red-700 ring-1 ring-red-100">
            <span class="mr-1">❌</span> Booking ini telah dibatalkan.
        </div>
    @else
        {{-- Horizontal on desktop, vertical on mobile --}}
        <div class="vault-tracking-steps">
            @foreach ($flowStatuses as $key => $step)
                @php
                    $index = array_search($key, $statusKeys);
                    $isDone = $currentIndex !== false && $index <= $currentIndex;
                    $isCurrent = $currentIndex !== false && $index === $currentIndex;
                @endphp
                <div class="vault-tracking-step {{ $isDone ? 'is-done' : '' }} {{ $isCurrent ? 'is-current' : '' }}">
                    <div class="vault-tracking-dot">
                        <span class="vault-tracking-dot-inner">
                            @if ($isDone)
                                @if ($isCurrent)
                                    <span class="text-sm">{{ $step['icon'] }}</span>
                                @else
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            @else
                                <span class="text-[10px] font-black">{{ $index + 1 }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="vault-tracking-label">
                        <span class="vault-tracking-title">{{ $step['label'] }}</span>
                        <span class="vault-tracking-desc">{{ $step['desc'] }}</span>
                    </div>
                    @if (! $loop->last)
                        <div class="vault-tracking-line {{ $isDone && $index < $currentIndex ? 'is-done' : '' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
