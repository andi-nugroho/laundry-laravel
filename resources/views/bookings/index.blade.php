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

    @php
        $filterAction = request()->routeIs('monitoring.*') ? route('monitoring.index') : route('bookings.index');
        $sortOptions = [
            'terbaru' => 'Terbaru',
            'terlama' => 'Terlama',
            'total_terbesar' => 'Total terbesar',
            'total_terkecil' => 'Total terkecil',
        ];
    @endphp

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <section class="rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] p-5 shadow-[0_18px_45px_rgba(24,21,18,0.08)] sm:p-6" x-data="{ open: false }">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="text-[0.68rem] font-black uppercase tracking-[0.14em] text-[#FF6626]">Filter Data</div>
                        <h3 class="mt-1 text-lg font-black text-neutral-950">Filter Booking</h3>
                        <p class="mt-1 text-sm font-semibold text-neutral-500">Menampilkan {{ number_format($bookings->total(), 0, ',', '.') }} booking</p>
                    </div>

                    <button type="button" class="vault-action-secondary md:hidden" @click="open = ! open" x-text="open ? 'Tutup Filter' : 'Buka Filter'"></button>
                </div>

                @if (count($activeFilters) > 0)
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($activeFilters as $activeFilter)
                            <span class="inline-flex rounded-full border border-[#E8DCCB] bg-[#FBF3E7] px-3 py-1 text-xs font-black capitalize text-neutral-700">
                                {{ $activeFilter }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <form method="GET" action="{{ $filterAction }}" class="mt-5 hidden md:block" :class="open ? '!block' : ''">
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="md:col-span-2">
                            <x-input-label for="search" value="Search" />
                            <x-text-input
                                id="search"
                                name="search"
                                type="search"
                                class="mt-1 block w-full"
                                placeholder="Kode booking, customer, atau layanan"
                                value="{{ $filters['search'] }}"
                            />
                        </div>

                        <div>
                            <x-input-label for="status" value="Status Laundry" />
                            <select id="status" name="status" class="mt-1 block w-full">
                                <option value="all" @selected($filters['status'] === 'all')>All</option>
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" @selected($filters['status'] === $status)>
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="payment_status" value="Status Pembayaran" />
                            <select id="payment_status" name="payment_status" class="mt-1 block w-full">
                                <option value="all" @selected($filters['payment_status'] === 'all')>All</option>
                                @foreach ($paymentStatusOptions as $paymentStatus)
                                    <option value="{{ $paymentStatus }}" @selected($filters['payment_status'] === $paymentStatus)>
                                        {{ ucfirst($paymentStatus) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="pickup_type" value="Pickup Type" />
                            <select id="pickup_type" name="pickup_type" class="mt-1 block w-full">
                                <option value="all" @selected($filters['pickup_type'] === 'all')>All</option>
                                @foreach ($pickupTypeOptions as $pickupType)
                                    <option value="{{ $pickupType }}" @selected($filters['pickup_type'] === $pickupType)>
                                        {{ ucwords(str_replace('_', ' ', $pickupType)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="date_from" value="Tanggal Awal" />
                            <x-text-input id="date_from" name="date_from" type="date" class="mt-1 block w-full" value="{{ $filters['date_from'] }}" />
                        </div>

                        <div>
                            <x-input-label for="date_to" value="Tanggal Akhir" />
                            <x-text-input id="date_to" name="date_to" type="date" class="mt-1 block w-full" value="{{ $filters['date_to'] }}" />
                        </div>

                        <div>
                            <x-input-label for="sort" value="Sort" />
                            <select id="sort" name="sort" class="mt-1 block w-full">
                                @foreach ($sortOptions as $value => $label)
                                    <option value="{{ $value }}" @selected($filters['sort'] === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                        <button type="submit" class="vault-action-primary justify-center">
                            Terapkan Filter
                        </button>
                        <a href="{{ $filterAction }}" class="vault-action-secondary justify-center">
                            Reset
                        </a>
                    </div>
                </form>
            </section>

            <x-list-panel storage-key="vaultBookingsView" title="{{ request()->routeIs('monitoring.*') ? 'Status Laundry' : 'Daftar Booking' }}" description="Mode table untuk operasi cepat, mode card untuk detail vertikal yang nyaman.">
                <x-slot name="table">
                    <div class="max-w-full overflow-x-auto px-1 py-1">
                        <table class="min-w-[820px] vault-table-compact divide-y divide-gray-200">
                            <thead class="sticky top-0 z-10 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left">Kode</th>
                                    <th scope="col" class="px-3 py-3 text-left">Pelanggan</th>
                                    <th scope="col" class="px-3 py-3 text-left">Layanan</th>
                                    <th scope="col" class="px-3 py-3 text-left">Tanggal</th>
                                    <th scope="col" class="px-3 py-3 text-right">Total</th>
                                    <th scope="col" class="px-3 py-3 text-left">Status</th>
                                    <th scope="col" class="px-3 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td class="vault-nowrap px-3 py-3">
                                            <div class="text-sm font-black text-neutral-900">{{ $booking->booking_code }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-sm font-medium text-neutral-600">
                                            <div class="vault-truncate max-w-[140px]">{{ $booking->customer?->name ?? '-' }}</div>
                                        </td>
                                        <td class="px-3 py-3 text-sm font-medium text-neutral-600">
                                            <div class="vault-truncate max-w-[120px]">{{ $booking->service?->name ?? '-' }}</div>
                                        </td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-medium text-neutral-600">
                                            {{ $booking->booking_date?->format('d M Y') }}
                                        </td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-bold text-neutral-900 text-right">
                                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-3">
                                            <div class="space-y-2">
                                                @include('bookings._status-badge', ['status' => $booking->status])
                                                @include('bookings._status-form', ['booking' => $booking, 'class' => 'flex flex-wrap items-center gap-2'])
                                            </div>
                                        </td>
                                        <td class="px-3 py-3">
                                            <div class="vault-action-group justify-end">
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
                                        <td colspan="7" class="px-6 py-12 text-center text-sm font-medium text-neutral-500">
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
                                    <x-card-field label="Estimasi" :value="$booking->estimated_finish_date?->format('d M Y') ?? '-'" />
                                    <x-card-field label="Berat" :value="$booking->weight ? number_format($booking->weight, 2, ',', '.') . ' kg' : '-'" />
                                    <x-card-field label="Total" :value="'Rp '.number_format($booking->total_price, 0, ',', '.')" />
                                    <x-card-field label="Pickup" :value="str_replace('_', ' ', ucfirst($booking->pickup_type))" />
                                    <div class="sm:col-span-2 lg:col-span-3">
                                        <x-card-field label="Status Update">
                                            @include('bookings._status-form', ['booking' => $booking, 'class' => 'mt-2 flex flex-col gap-2 sm:flex-row sm:items-center'])
                                        </x-card-field>
                                    </div>
                                </div>

                                <div class="mt-5 vault-action-group">
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
                            <div class="col-span-full rounded-3xl border border-dashed border-[#E8DCCB] p-8 text-center text-sm font-medium text-neutral-500">
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
