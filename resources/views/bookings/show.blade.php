<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Booking Laundry') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ $booking->booking_code }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('bookings.index') }}">
                    <x-secondary-button type="button">Kembali</x-secondary-button>
                </a>

                @can('update', $booking)
                    <a href="{{ route('bookings.edit', $booking) }}">
                        <x-primary-button type="button">Edit</x-primary-button>
                    </a>
                @endcan

                @if ((Auth::user()->isAdmin() || Auth::user()->isKasir()) && (! $booking->payment || $booking->payment->payment_status !== \App\Models\Payment::STATUS_PAID))
                    <a href="{{ $booking->payment ? route('payments.edit', $booking->payment) : route('payments.create', ['booking_id' => $booking->id]) }}">
                        <x-primary-button type="button">{{ $booking->payment ? 'Bayar' : 'Input Pembayaran' }}</x-primary-button>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700 ring-1 ring-green-100">
                    {{ session('success') }}
                </div>
            @endif

            @include('bookings._status-timeline', ['booking' => $booking])

            <div class="mt-6 bg-white shadow sm:rounded-lg">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Kode Booking</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $booking->booking_code }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Pelanggan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $booking->customer?->name ?? '-' }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">User</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if ($booking->user)
                                {{ $booking->user->name }} <span class="text-gray-500">({{ $booking->user->email }})</span>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Layanan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $booking->service?->name ?? '-' }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Booking</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $booking->booking_date?->format('d M Y') }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Estimasi Selesai</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $booking->estimated_finish_date?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Berat</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $booking->weight ? number_format($booking->weight, 2, ',', '.') . ' kg' : '-' }}
                        </dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Total Harga</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Pembayaran</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if ($booking->payment)
                                <a href="{{ route('payments.show', $booking->payment) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $booking->payment->payment_code }}
                                </a>
                                <span class="text-gray-500">({{ ucfirst($booking->payment->payment_status) }})</span>
                                <a href="{{ route('payments.invoice', $booking->payment) }}" class="ms-2 inline-flex rounded-xl border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-black text-emerald-700 transition hover:bg-emerald-100">
                                    Download PDF
                                </a>
                            @else
                                Belum ada pembayaran
                            @endif
                        </dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Tipe Pengambilan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ str_replace('_', ' ', ucfirst($booking->pickup_type)) }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 space-y-3 sm:col-span-2 sm:mt-0">
                            @include('bookings._status-badge', ['status' => $booking->status])
                            @include('bookings._status-form', ['booking' => $booking, 'class' => 'flex flex-col gap-2 sm:flex-row sm:items-center'])
                        </dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $booking->notes ?? '-' }}</dd>
                    </div>
                </dl>

                @can('delete', $booking)
                    <div class="border-t border-gray-100 px-4 py-5 sm:px-8">
                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus booking ini?');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>Hapus Booking</x-danger-button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
