<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-black leading-tight text-neutral-900">
                    {{ Auth::user()->isUser() ? __('Riwayat Pembayaran') : __('Transaksi Pembayaran') }}
                </h2>
                <p class="mt-1 text-sm font-medium text-neutral-500">
                    {{ Auth::user()->isUser() ? 'Lihat pembayaran booking laundry Anda' : 'Kelola transaksi pembayaran laundry' }}
                </p>
            </div>

            @can('create', \App\Models\Payment::class)
                <a href="{{ route('payments.create') }}">
                    <x-primary-button type="button">
                        + Input Pembayaran
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

            <x-list-panel storage-key="vaultPaymentsView" title="Daftar Pembayaran" description="Cek transaksi dalam mode table atau invoice-like card.">
                <x-slot name="table">
                    <div class="max-w-full overflow-x-auto px-1 py-1">
                        <table class="min-w-[780px] vault-table-compact divide-y divide-gray-200">
                            <thead class="sticky top-0 z-10 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left">Kode</th>
                                    <th scope="col" class="px-3 py-3 text-left">Booking</th>
                                    <th scope="col" class="px-3 py-3 text-left">Pelanggan</th>
                                    <th scope="col" class="px-3 py-3 text-right">Tagihan</th>
                                    <th scope="col" class="px-3 py-3 text-left">Status</th>
                                    <th scope="col" class="px-3 py-3 text-left">Metode</th>
                                    <th scope="col" class="px-3 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($payments as $payment)
                                    @php
                                        $badgeStatus = Auth::user()->isUser()
                                            && $payment->payment_status === \App\Models\Payment::STATUS_UNPAID
                                            && $payment->payment_method !== \App\Models\Payment::METHOD_CASH
                                                ? 'pending_confirmation'
                                                : $payment->payment_status;
                                    @endphp
                                    <tr>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-black text-neutral-900">{{ $payment->payment_code }}</td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-medium text-neutral-600">{{ $payment->booking?->booking_code ?? '-' }}</td>
                                        <td class="px-3 py-3 text-sm font-medium text-neutral-600">
                                            <div class="vault-truncate max-w-[140px]">{{ $payment->booking?->customer?->name ?? '-' }}</div>
                                        </td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-bold text-neutral-900 text-right">Rp {{ number_format($payment->total_bill, 0, ',', '.') }}</td>
                                        <td class="vault-nowrap px-3 py-3">
                                            @include('payments._status-badge', ['status' => $badgeStatus, 'payment' => $payment])
                                        </td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-medium capitalize text-neutral-600">{{ $payment->payment_method }}</td>
                                        <td class="px-3 py-3">
                                            <div class="vault-action-group justify-end">
                                                @can('view', $payment)
                                                    <a href="{{ route('payments.show', $payment) }}" class="vault-action-secondary">Detail</a>
                                                    @if (! Auth::user()->isUser() || $payment->payment_status === \App\Models\Payment::STATUS_PAID)
                                                        <a href="{{ route('payments.invoice', $payment) }}" class="vault-action-success">PDF</a>
                                                    @endif
                                                @endcan

                                                @if (Auth::user()->isUser() && in_array($payment->payment_status, [\App\Models\Payment::STATUS_UNPAID, \App\Models\Payment::STATUS_PARTIAL]))
                                                    <a href="{{ route('payments.pay', $payment) }}" class="vault-action-primary !bg-[#FF6626] hover:!bg-[#e55c22] !border-transparent">Bayar</a>
                                                @endif

                                                @can('update', $payment)
                                                    <a href="{{ route('payments.edit', $payment) }}" class="vault-action-primary">
                                                        {{ in_array($payment->payment_status, [\App\Models\Payment::STATUS_UNPAID, \App\Models\Payment::STATUS_PARTIAL], true) ? 'Bayar' : 'Edit' }}
                                                    </a>
                                                @endcan

                                                @can('delete', $payment)
                                                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pembayaran ini?');">
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
                                            Belum ada transaksi pembayaran.
                                            @can('create', \App\Models\Payment::class)
                                                <a href="{{ route('payments.create') }}" class="font-black text-[#FF6626] hover:underline">Input pembayaran pertama</a>
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
                        @forelse ($payments as $payment)
                            @php
                                $badgeStatus = Auth::user()->isUser()
                                    && $payment->payment_status === \App\Models\Payment::STATUS_UNPAID
                                    && $payment->payment_method !== \App\Models\Payment::METHOD_CASH
                                        ? 'pending_confirmation'
                                        : $payment->payment_status;
                            @endphp
                            <article class="vault-record-card">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <h3 class="text-base font-black text-neutral-950">{{ $payment->payment_code }}</h3>
                                        <p class="mt-1 text-sm font-medium text-neutral-500 truncate">{{ $payment->booking?->booking_code ?? '-' }} — {{ $payment->booking?->customer?->name ?? '-' }}</p>
                                    </div>
                                    @include('payments._status-badge', ['status' => $badgeStatus, 'payment' => $payment])
                                </div>

                                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <x-card-field label="Layanan" :value="$payment->booking?->service?->name ?? '-'" />
                                    <x-card-field label="Tagihan" :value="'Rp '.number_format($payment->total_bill, 0, ',', '.')" />
                                    <x-card-field label="Dibayar" :value="'Rp '.number_format($payment->amount_paid, 0, ',', '.')" />
                                    <x-card-field label="Kembalian" :value="'Rp '.number_format($payment->change_amount, 0, ',', '.')" />
                                    <x-card-field label="Metode" :value="ucfirst($payment->payment_method)" />
                                    <x-card-field label="Tanggal" :value="$payment->payment_date?->format('d M Y H:i') ?? '-'" />
                                    <x-card-field label="Petugas" :value="$payment->processedBy?->name ?? '-'" />
                                </div>

                                <div class="mt-5 vault-action-group">
                                    @can('view', $payment)
                                        <a href="{{ route('payments.show', $payment) }}" class="vault-action-secondary">Detail</a>
                                        @if (! Auth::user()->isUser() || $payment->payment_status === \App\Models\Payment::STATUS_PAID)
                                            <a href="{{ route('payments.invoice', $payment) }}" class="vault-action-success">Download PDF</a>
                                        @endif
                                    @endcan

                                    @if (Auth::user()->isUser() && in_array($payment->payment_status, [\App\Models\Payment::STATUS_UNPAID, \App\Models\Payment::STATUS_PARTIAL]))
                                        <a href="{{ route('payments.pay', $payment) }}" class="vault-action-primary !bg-[#FF6626] hover:!bg-[#e55c22] !border-transparent">Bayar</a>
                                    @endif

                                    @can('update', $payment)
                                        <a href="{{ route('payments.edit', $payment) }}" class="vault-action-primary">
                                            {{ in_array($payment->payment_status, [\App\Models\Payment::STATUS_UNPAID, \App\Models\Payment::STATUS_PARTIAL], true) ? 'Bayar' : 'Edit' }}
                                        </a>
                                    @endcan

                                    @can('delete', $payment)
                                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pembayaran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="vault-action-danger">Hapus</button>
                                        </form>
                                    @endcan
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full rounded-3xl border border-dashed border-[#E8DCCB] p-8 text-center text-sm font-medium text-neutral-500">
                                Belum ada transaksi pembayaran.
                                @can('create', \App\Models\Payment::class)
                                    <a href="{{ route('payments.create') }}" class="font-black text-[#FF6626] hover:underline">Input pembayaran pertama</a>
                                @endcan
                            </div>
                        @endforelse
                    </div>
                </x-slot>

                @if ($payments->hasPages())
                    <x-slot name="footer">
                        {{ $payments->links() }}
                    </x-slot>
                @endif
            </x-list-panel>
        </div>
    </div>
</x-app-layout>
