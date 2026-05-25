<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Laporan Pendapatan') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">Ringkasan pendapatan dan piutang pembayaran laundry</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-4 sm:p-6">
                <form method="GET" action="{{ route('reports.revenue') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card label="Pendapatan Paid" :value="'Rp '.number_format($stats['total_revenue_paid'], 0, ',', '.')" color="emerald" />
                <x-stat-card label="Piutang Unpaid/Partial" :value="'Rp '.number_format($stats['total_receivables'], 0, ',', '.')" color="rose" />
                <x-stat-card label="Transaksi Paid" :value="number_format($stats['paid_count'])" color="indigo" />
                <x-stat-card label="Transaksi Tertunda" :value="number_format($stats['pending_count'])" color="amber" />
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Ringkasan Pendapatan per Metode</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Pendapatan Paid</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($revenueByMethod as $method => $total)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ $method }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada pendapatan paid sesuai filter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
