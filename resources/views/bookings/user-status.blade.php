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
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @forelse ($bookings as $booking)
                <section class="overflow-hidden rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] shadow-[0_18px_45px_rgba(24,21,18,0.08)]">
                    <div class="flex flex-col gap-4 border-b border-[#E8DCCB] bg-[#FBF3E7]/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                        <div>
                            <div class="text-[0.68rem] font-black uppercase tracking-[0.14em] text-neutral-400">Booking</div>
                            <h3 class="mt-1 text-lg font-black text-neutral-950">{{ $booking->booking_code }}</h3>
                            <p class="mt-1 text-sm font-medium text-neutral-500">{{ $booking->service?->name ?? '-' }} - {{ $booking->booking_date?->format('d M Y') }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            @include('bookings._status-badge', ['status' => $booking->status])
                            @can('view', $booking)
                                <a href="{{ route('bookings.show', $booking) }}" class="vault-action-secondary">Detail</a>
                            @endcan
                        </div>
                    </div>

                    <div class="grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">
                        <x-card-field label="Customer" :value="$booking->customer?->name ?? '-'" />
                        <x-card-field label="Berat" :value="$booking->weight ? number_format($booking->weight, 2, ',', '.') . ' kg' : '-'" />
                        <x-card-field label="Estimasi Selesai" :value="$booking->estimated_finish_date?->format('d M Y') ?? '-'" />
                        <x-card-field label="Pembayaran" :value="$booking->payment ? ucfirst($booking->payment->payment_status) : '-'" />
                    </div>

                    <div class="px-5 pb-5">
                        @include('bookings._status-timeline', ['booking' => $booking])
                    </div>
                </section>
            @empty
                <div class="rounded-3xl border border-dashed border-[#E8DCCB] bg-[#FFF9F1] p-10 text-center shadow-[0_18px_45px_rgba(24,21,18,0.06)]">
                    <h3 class="text-lg font-black text-neutral-950">Belum ada cucian aktif</h3>
                    <p class="mt-2 text-sm font-medium text-neutral-500">Booking aktif Anda akan muncul di sini.</p>
                    <a href="{{ route('bookings.create') }}" class="mt-5 inline-flex vault-action-primary">Buat Booking</a>
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
