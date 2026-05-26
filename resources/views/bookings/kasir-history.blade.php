<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-black leading-tight text-neutral-900">
                {{ __('Riwayat Laundry') }}
            </h2>
            <p class="mt-1 text-sm font-medium text-neutral-500">Booking yang sudah selesai, diambil, atau dibatalkan.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            <x-list-panel storage-key="vaultKasirHistoryView" title="Riwayat Booking" description="Arsip booking dalam mode table atau card.">
                <x-slot name="table">
                    <div class="max-w-full overflow-x-auto px-1 py-1">
                        <table class="min-w-[820px] vault-table-compact divide-y divide-gray-200">
                            <thead class="sticky top-0 z-10 bg-gray-50">
                                <tr>
                                    <th class="px-3 py-3 text-left">Kode</th>
                                    <th class="px-3 py-3 text-left">Pelanggan</th>
                                    <th class="px-3 py-3 text-left">Layanan</th>
                                    <th class="px-3 py-3 text-left">Tanggal</th>
                                    <th class="px-3 py-3 text-right">Total</th>
                                    <th class="px-3 py-3 text-left">Status</th>
                                    <th class="px-3 py-3 text-left">Pembayaran</th>
                                    <th class="px-3 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-black text-neutral-900">{{ $booking->booking_code }}</td>
                                        <td class="px-3 py-3 text-sm font-medium text-neutral-600">
                                            <div class="vault-truncate max-w-[140px]">{{ $booking->customer?->name ?? '-' }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-sm font-medium text-neutral-600">
                                            <div class="vault-truncate max-w-[120px]">{{ $booking->service?->name ?? '-' }}</div>
                                        </td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-medium text-neutral-600">{{ $booking->booking_date?->format('d M Y') }}</td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-bold text-neutral-900 text-right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                        <td class="vault-nowrap px-3 py-3">@include('bookings._status-badge', ['status' => $booking->status])</td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-medium text-neutral-600">{{ $booking->payment ? ucfirst($booking->payment->payment_status) : '-' }}</td>
                                        <td class="px-3 py-3">
                                            <div class="vault-action-group justify-end">
                                                @can('view', $booking)
                                                    <a href="{{ route('bookings.show', $booking) }}" class="vault-action-secondary">Detail</a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-sm font-medium text-neutral-500">Belum ada riwayat booking.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot>

                <x-slot name="cards">
                    <div class="vault-card-list">
                        @forelse ($bookings as $booking)
                            <article class="vault-record-card">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <h3 class="text-base font-black text-neutral-950">{{ $booking->booking_code }}</h3>
                                        <p class="mt-1 text-sm font-medium text-neutral-500 truncate">{{ $booking->customer?->name ?? '-' }} — {{ $booking->service?->name ?? '-' }}</p>
                                    </div>
                                    @include('bookings._status-badge', ['status' => $booking->status])
                                </div>

                                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <x-card-field label="Tanggal" :value="$booking->booking_date?->format('d M Y') ?? '-'" />
                                    <x-card-field label="Total" :value="'Rp '.number_format($booking->total_price, 0, ',', '.')" />
                                    <x-card-field label="Pembayaran" :value="$booking->payment ? ucfirst($booking->payment->payment_status) : '-'" />
                                </div>

                                <div class="mt-5 vault-action-group">
                                    @can('view', $booking)
                                        <a href="{{ route('bookings.show', $booking) }}" class="vault-action-secondary">Detail</a>
                                    @endcan
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full rounded-3xl border border-dashed border-[#E8DCCB] p-8 text-center text-sm font-medium text-neutral-500">
                                Belum ada riwayat booking.
                            </div>
                        @endforelse
                    </div>
                </x-slot>

                @if ($bookings->hasPages())
                    <x-slot name="footer">
                        {{ $bookings->links() }}
                    </x-slot>
                @endif
            </x-list-panel>
        </div>
    </div>
</x-app-layout>
