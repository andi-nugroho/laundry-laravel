<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-black leading-tight text-neutral-900">
                    {{ request()->routeIs('monitoring.*') ? __('Monitoring Laundry') : __('Booking Laundry') }}
                </h2>
                <p class="mt-1 text-sm font-medium text-neutral-500">
                    {{ Auth::user()->isUser() ? 'Pantau status laundry Anda' : 'Kelola booking dan status laundry pelanggan' }}
                </p>
            </div>

            @can('create', \App\Models\Booking::class)
                <a href="{{ route('bookings.create') }}">
                    <x-primary-button type="button">
                        + Tambah Booking
                    </x-primary-button>
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <x-list-panel storage-key="vaultBookingsView" title="{{ request()->routeIs('monitoring.*') ? 'Status Laundry' : 'Daftar Booking' }}" description="Mode table untuk operasi cepat, mode card untuk detail vertikal yang nyaman.">
                <x-slot name="table">
                    <div class="max-w-full overflow-x-auto px-1 py-1">
                        <table class="min-w-[1280px] divide-y divide-gray-200">
                            <thead class="sticky top-0 z-10 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left">Kode</th>
                                    <th scope="col" class="px-6 py-4 text-left">Pelanggan</th>
                                    <th scope="col" class="px-6 py-4 text-left">Layanan</th>
                                    <th scope="col" class="px-6 py-4 text-left">Tanggal</th>
                                    <th scope="col" class="px-6 py-4 text-left">Berat</th>
                                    <th scope="col" class="px-6 py-4 text-left">Total</th>
                                    <th scope="col" class="px-6 py-4 text-left">Pickup</th>
                                    <th scope="col" class="px-6 py-4 text-left">Status</th>
                                    <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm font-black text-neutral-900">{{ $booking->booking_code }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-neutral-600">
                                            {{ $booking->customer?->name ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-neutral-600">
                                            {{ $booking->service?->name ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-neutral-600">
                                            <div>{{ $booking->booking_date?->format('d M Y') }}</div>
                                            <div class="text-xs text-neutral-400">Est. {{ $booking->estimated_finish_date?->format('d M Y') ?? '-' }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-neutral-600">
                                            {{ $booking->weight ? number_format($booking->weight, 2, ',', '.') . ' kg' : '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-neutral-900">
                                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium capitalize text-neutral-600">
                                            {{ str_replace('_', ' ', ucfirst($booking->pickup_type)) }}
                                        </td>
                                        <td class="min-w-64 px-6 py-4">
                                            <div class="space-y-2">
                                                @include('bookings._status-badge', ['status' => $booking->status])
                                                @include('bookings._status-form', ['booking' => $booking, 'class' => 'flex flex-wrap items-center gap-2'])
                                            </div>
                                        </td>
                                        <td class="min-w-72 px-6 py-4">
                                            <div class="flex flex-wrap items-center justify-end gap-2">
                                                @can('view', $booking)
                                                    <a href="{{ route('bookings.show', $booking) }}" class="vault-action-secondary">Detail</a>
                                                @endcan

                                                @can('update', $booking)
                                                    <a href="{{ route('bookings.edit', $booking) }}" class="vault-action-primary">Edit</a>
                                                @endcan

                                                @if ((Auth::user()->isAdmin() || Auth::user()->isKasir()) && (! $booking->payment || $booking->payment->payment_status !== \App\Models\Payment::STATUS_PAID))
                                                    @if ($booking->payment)
                                                        <a href="{{ route('payments.edit', $booking->payment) }}" class="vault-action-success">Bayar</a>
                                                    @else
                                                        <a href="{{ route('payments.create', ['booking_id' => $booking->id]) }}" class="vault-action-success">Input Pembayaran</a>
                                                    @endif
                                                @endif

                                                @can('delete', $booking)
                                                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus booking ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="vault-action-danger">Hapus</button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center text-sm font-medium text-neutral-500">
                                            Belum ada booking laundry.
                                            @can('create', \App\Models\Booking::class)
                                                <a href="{{ route('bookings.create') }}" class="font-black text-[#FF6626] hover:underline">Tambah booking pertama</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot>

                <x-slot name="cards">
                    <div class="grid gap-4 p-4 xl:grid-cols-2">
                        @forelse ($bookings as $booking)
                            <article class="vault-record-card">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <h3 class="text-base font-black text-neutral-950">{{ $booking->booking_code }}</h3>
                                        <p class="mt-1 text-sm font-medium text-neutral-500">{{ $booking->customer?->name ?? '-' }} - {{ $booking->service?->name ?? '-' }}</p>
                                    </div>
                                    @include('bookings._status-badge', ['status' => $booking->status])
                                </div>

                                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <x-card-field label="Tanggal" :value="$booking->booking_date?->format('d M Y') ?? '-'" />
                                    <x-card-field label="Estimasi" :value="$booking->estimated_finish_date?->format('d M Y') ?? '-'" />
                                    <x-card-field label="Berat" :value="$booking->weight ? number_format($booking->weight, 2, ',', '.') . ' kg' : '-'" />
                                    <x-card-field label="Total" :value="'Rp '.number_format($booking->total_price, 0, ',', '.')" />
                                    <x-card-field label="Pickup" :value="str_replace('_', ' ', ucfirst($booking->pickup_type))" />
                                    <x-card-field label="Status Update">
                                        @include('bookings._status-form', ['booking' => $booking, 'class' => 'mt-1 flex flex-col gap-2 sm:flex-row sm:items-center'])
                                    </x-card-field>
                                </div>

                                <div class="mt-5 flex flex-wrap gap-2">
                                    @can('view', $booking)
                                        <a href="{{ route('bookings.show', $booking) }}" class="vault-action-secondary">Detail</a>
                                    @endcan

                                    @can('update', $booking)
                                        <a href="{{ route('bookings.edit', $booking) }}" class="vault-action-primary">Edit</a>
                                    @endcan

                                    @if ((Auth::user()->isAdmin() || Auth::user()->isKasir()) && (! $booking->payment || $booking->payment->payment_status !== \App\Models\Payment::STATUS_PAID))
                                        @if ($booking->payment)
                                            <a href="{{ route('payments.edit', $booking->payment) }}" class="vault-action-success">Bayar</a>
                                        @else
                                            <a href="{{ route('payments.create', ['booking_id' => $booking->id]) }}" class="vault-action-success">Input Pembayaran</a>
                                        @endif
                                    @endif

                                    @can('delete', $booking)
                                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus booking ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="vault-action-danger">Hapus</button>
                                        </form>
                                    @endcan
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-[#E8DCCB] p-8 text-center text-sm font-medium text-neutral-500 xl:col-span-2">
                                Belum ada booking laundry.
                                @can('create', \App\Models\Booking::class)
                                    <a href="{{ route('bookings.create') }}" class="font-black text-[#FF6626] hover:underline">Tambah booking pertama</a>
                                @endcan
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
