<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard Operasional
                </h2>
                <p class="mt-1 text-sm text-gray-500">Ringkasan aktivitas kasir hari ini</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700 ring-1 ring-amber-100">
                Kasir
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8" data-dashboard-polling x-data="dashboardPolling()" x-init="init()">
            <x-dashboard-polling-bar />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card stat="booking_today" label="Booking Masuk Hari Ini" :value="number_format($stats['booking_today'])" asset="assets/calendar.webp" color="sky" />
                <x-stat-card stat="laundry_processing" label="Laundry Sedang Diproses" :value="number_format($stats['laundry_processing'])" asset="assets/washing-machine.webp" color="amber" />
                <x-stat-card stat="payment_pending" label="Payment Belum Lunas" :value="number_format($stats['payment_pending'])" asset="assets/receipt.webp" color="rose" />
                <x-stat-card stat="payment_today_total" label="Pembayaran Hari Ini" :value="'Rp '.number_format($stats['payment_today_total'], 0, ',', '.')" asset="assets/wallet.webp" color="emerald" />
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-base font-semibold text-gray-900">5 Booking Perlu Diproses</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full vault-dashboard-table divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Layanan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($processBookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $booking->booking_code }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->customer?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->service?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Tidak ada booking yang perlu diproses.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-base font-semibold text-gray-900">5 Payment Unpaid/Partial</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full vault-dashboard-table divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($pendingPayments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $payment->payment_code }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->booking?->booking_code ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm vault-nowrap text-gray-900">Rp {{ number_format(max($payment->total_bill - $payment->amount_paid, 0), 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($payment->payment_status) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Tidak ada payment tertunda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
