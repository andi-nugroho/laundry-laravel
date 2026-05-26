<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-black leading-tight text-neutral-900">
                {{ __('Status Cucian') }}
            </h2>
            <p class="mt-1 text-sm font-medium text-neutral-500">Pantau progres booking laundry aktif milik Anda.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @forelse ($bookings as $booking)
                <section class="vault-tracking-card">
                    {{-- Header --}}
                    <div class="vault-tracking-card-header">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#FF6626] text-white shadow-md shadow-orange-500/20">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-lg font-black text-neutral-950 truncate">{{ $booking->booking_code }}</h3>
                                        <p class="text-sm font-medium text-neutral-500 truncate">{{ $booking->service?->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                @include('bookings._status-badge', ['status' => $booking->status])
                                @can('view', $booking)
                                    <a href="{{ route('bookings.show', $booking) }}" class="vault-action-secondary">Lihat Detail</a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    {{-- Info Grid --}}
                    <div class="grid gap-3 px-5 py-4 sm:grid-cols-2 lg:grid-cols-4 sm:px-6">
                        <div class="vault-tracking-info-item">
                            <div class="vault-tracking-info-label">Pelanggan</div>
                            <div class="vault-tracking-info-value truncate">{{ $booking->customer?->name ?? '-' }}</div>
                        </div>
                        <div class="vault-tracking-info-item">
                            <div class="vault-tracking-info-label">Berat</div>
                            <div class="vault-tracking-info-value">{{ $booking->weight ? number_format($booking->weight, 2, ',', '.') . ' kg' : '-' }}</div>
                        </div>
                        <div class="vault-tracking-info-item">
                            <div class="vault-tracking-info-label">Estimasi Selesai</div>
                            <div class="vault-tracking-info-value">{{ $booking->estimated_finish_date?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="vault-tracking-info-item">
                            <div class="vault-tracking-info-label">Pembayaran</div>
                            <div class="vault-tracking-info-value">
                                @if ($booking->payment)
                                    @php
                                        $payColors = [
                                            'paid' => 'text-emerald-700',
                                            'unpaid' => 'text-red-600',
                                            'partial' => 'text-amber-600',
                                        ];
                                    @endphp
                                    <span class="{{ $payColors[$booking->payment->payment_status] ?? 'text-neutral-600' }}">{{ ucfirst($booking->payment->payment_status) }}</span>
                                @else
                                    <span class="text-neutral-400">—</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="border-t border-[#E8DCCB] px-5 py-5 sm:px-6">
                        @include('bookings._status-timeline', ['booking' => $booking])
                    </div>
                </section>
            @empty
                <div class="rounded-3xl border border-dashed border-[#E8DCCB] bg-[#FFF9F1] p-10 text-center shadow-[0_18px_45px_rgba(24,21,18,0.06)]">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[#FBF3E7] text-3xl">🧺</div>
                    <h3 class="mt-4 text-lg font-black text-neutral-950">Belum ada cucian aktif</h3>
                    <p class="mt-2 text-sm font-medium text-neutral-500">Booking aktif Anda akan muncul di sini dengan tracking real-time.</p>
                    <a href="{{ route('bookings.create') }}" class="mt-5 inline-flex vault-action-primary">Buat Booking Baru</a>
                </div>
            @endforelse

            @if ($bookings->hasPages())
                <div class="rounded-2xl border border-[#E8DCCB] bg-[#FFF9F1] px-5 py-4">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
