<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-black leading-tight text-neutral-900">
                    {{ __('Laporan Transaksi') }}
                </h2>
                <p class="mt-1 text-sm font-medium text-neutral-500">Filter dan pantau transaksi pembayaran laundry</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] p-4 shadow-[0_18px_45px_rgba(24,21,18,0.08)] sm:p-6">
                <form method="GET" action="{{ route('reports.transactions') }}" class="grid grid-cols-1 gap-4 md:grid-cols-5">
                    <div>
                        <x-input-label for="start_date" value="Tanggal Awal" />
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="$filters['start_date'] ?? ''" />
                    </div>
                    <div>
                        <x-input-label for="end_date" value="Tanggal Akhir" />
                        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="$filters['end_date'] ?? ''" />
                    </div>
                    <div>
                        <x-input-label for="payment_status" value="Status" />
                        <select id="payment_status" name="payment_status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Semua</option>
                            @foreach (\App\Models\Payment::STATUSES as $status)
                                <option value="{{ $status }}" @selected(($filters['payment_status'] ?? '') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="payment_method" value="Metode" />
                        <select id="payment_method" name="payment_method" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Semua</option>
                            @foreach (\App\Models\Payment::METHODS as $method)
                                <option value="{{ $method }}" @selected(($filters['payment_method'] ?? '') === $method)>{{ ucfirst($method) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <x-primary-button>Filter</x-primary-button>
                        <a href="{{ route('reports.transactions') }}">
                            <x-secondary-button type="button">Reset</x-secondary-button>
                        </a>
                    </div>
                </form>
            </div>

            <x-list-panel storage-key="vaultReportsTransactionsView" title="Data Transaksi" description="Tabel untuk audit lengkap, card untuk review cepat per transaksi.">
                <x-slot name="table">
                    <div class="max-w-full overflow-x-auto px-1 py-1">
                        <table class="min-w-[740px] vault-table-compact divide-y divide-gray-200">
                            <thead class="sticky top-0 z-10 bg-gray-50">
                                <tr>
                                    <th class="px-3 py-3 text-left">Payment</th>
                                    <th class="px-3 py-3 text-left">Booking</th>
                                    <th class="px-3 py-3 text-left">Customer</th>
                                    <th class="px-3 py-3 text-right">Tagihan</th>
                                    <th class="px-3 py-3 text-left">Status</th>
                                    <th class="px-3 py-3 text-left">Metode</th>
                                    <th class="px-3 py-3 text-left">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-black text-neutral-900">{{ $payment->payment_code }}</td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-medium text-neutral-600">{{ $payment->booking?->booking_code ?? '-' }}</td>
                                        <td class="px-3 py-3 text-sm font-medium text-neutral-600">
                                            <div class="vault-truncate max-w-[130px]">{{ $payment->booking?->customer?->name ?? '-' }}</div>
                                        </td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-bold text-neutral-900 text-right">Rp {{ number_format($payment->total_bill, 0, ',', '.') }}</td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-semibold text-neutral-600">{{ ucfirst($payment->payment_status) }}</td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-medium capitalize text-neutral-600">{{ $payment->payment_method }}</td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-medium text-neutral-600">{{ $payment->payment_date?->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-sm font-medium text-neutral-500">Tidak ada transaksi sesuai filter.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot>

                <x-slot name="cards">
                    <div class="vault-card-list">
                        @forelse ($payments as $payment)
                            <article class="vault-record-card">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <h3 class="text-base font-black text-neutral-950">{{ $payment->payment_code }}</h3>
                                        <p class="mt-1 text-sm font-medium text-neutral-500 truncate">{{ $payment->booking?->booking_code ?? '-' }} — {{ $payment->booking?->customer?->name ?? '-' }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-full bg-[#FBF3E7] px-2.5 py-1 text-xs font-black capitalize text-neutral-700">{{ $payment->payment_status }}</span>
                                </div>

                                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <x-card-field label="Layanan" :value="$payment->booking?->service?->name ?? '-'" />
                                    <x-card-field label="Tagihan" :value="'Rp '.number_format($payment->total_bill, 0, ',', '.')" />
                                    <x-card-field label="Dibayar" :value="'Rp '.number_format($payment->amount_paid, 0, ',', '.')" />
                                    <x-card-field label="Kembalian" :value="'Rp '.number_format($payment->change_amount, 0, ',', '.')" />
                                    <x-card-field label="Metode" :value="ucfirst($payment->payment_method)" />
                                    <x-card-field label="Tanggal" :value="$payment->payment_date?->format('d M Y H:i') ?? '-'" />
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full rounded-3xl border border-dashed border-[#E8DCCB] p-8 text-center text-sm font-medium text-neutral-500">
                                Tidak ada transaksi sesuai filter.
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
