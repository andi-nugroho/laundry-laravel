<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-black leading-tight text-neutral-900">
                    {{ __('Laporan Pendapatan') }}
                </h2>
                <p class="mt-1 text-sm font-medium text-neutral-500">Ringkasan pendapatan dan piutang pembayaran laundry</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] p-4 shadow-[0_18px_45px_rgba(24,21,18,0.08)] sm:p-6">
                <form method="GET" action="{{ route('reports.revenue') }}" class="grid grid-cols-1 gap-4 md:grid-cols-5">
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
                        <a href="{{ route('reports.revenue') }}">
                            <x-secondary-button type="button">Reset</x-secondary-button>
                        </a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <x-stat-card label="Pendapatan Paid" :value="'Rp '.number_format($stats['total_revenue_paid'], 0, ',', '.')" color="emerald" />
                <x-stat-card label="Piutang Unpaid/Partial" :value="'Rp '.number_format($stats['total_receivables'], 0, ',', '.')" color="rose" />
                <x-stat-card label="Transaksi Paid" :value="number_format($stats['paid_count'])" color="indigo" />
                <x-stat-card label="Transaksi Tertunda" :value="number_format($stats['pending_count'])" color="amber" />
            </div>

            <x-list-panel storage-key="vaultReportsRevenueView" title="Pendapatan per Metode" description="Ringkasan nominal paid berdasarkan metode pembayaran.">
                <x-slot name="table">
                    <div class="overflow-x-auto">
                        <table class="min-w-full vault-table-compact divide-y divide-gray-200">
                            <thead class="sticky top-0 z-10 bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left">Metode</th>
                                    <th class="px-6 py-4 text-left">Total Pendapatan Paid</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($revenueByMethod as $method => $total)
                                    <tr>
                                        <td class="px-3 py-3 text-sm font-bold capitalize text-neutral-700">{{ $method }}</td>
                                        <td class="vault-nowrap px-3 py-3 text-sm font-black text-neutral-900">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-12 text-center text-sm font-medium text-neutral-500">Belum ada pendapatan paid sesuai filter.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot>

                <x-slot name="cards">
                    <div class="grid gap-4 p-4 sm:grid-cols-2 xl:grid-cols-3">
                        @forelse ($revenueByMethod as $method => $total)
                            <article class="vault-record-card">
                                <div class="text-[0.68rem] font-black uppercase tracking-[0.12em] text-neutral-400">Metode</div>
                                <h3 class="mt-1 text-base font-black capitalize text-neutral-950">{{ $method }}</h3>
                                <div class="mt-5 rounded-2xl border border-[#E8DCCB] bg-[#FBF3E7] p-4">
                                    <div class="text-[0.68rem] font-black uppercase tracking-[0.12em] text-neutral-400">Total Pendapatan Paid</div>
                                    <div class="mt-1 text-2xl font-black text-neutral-950">Rp {{ number_format($total, 0, ',', '.') }}</div>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full rounded-3xl border border-dashed border-[#E8DCCB] p-8 text-center text-sm font-medium text-neutral-500">
                                Belum ada pendapatan paid sesuai filter.
                            </div>
                        @endforelse
                    </div>
                </x-slot>
            </x-list-panel>
        </div>
    </div>
</x-app-layout>
