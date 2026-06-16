<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard Statistik
                </h2>
                <p class="mt-1 text-sm text-gray-500">Ringkasan operasional laundry berdasarkan data real</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 ring-1 ring-indigo-100">
                Admin
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8" data-dashboard-polling x-data="dashboardPolling()" x-init="init()">
            <x-dashboard-polling-bar />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <x-stat-card stat="total_customers" label="Total Customers" :value="number_format($stats['total_customers'])" asset="assets/laundry-basket.webp" color="indigo" />
                <x-stat-card stat="total_services_active" label="Services Aktif" :value="number_format($stats['total_services_active'])" asset="assets/detergent.webp" color="sky" />
                <x-stat-card stat="total_bookings" label="Total Bookings" :value="number_format($stats['total_bookings'])" asset="assets/calendar.webp" color="slate" />
                <x-stat-card stat="total_bookings_today" label="Bookings Hari Ini" :value="number_format($stats['total_bookings_today'])" asset="assets/monitor-graph.webp" color="amber" />
                <x-stat-card stat="booking_masuk" label="Booking Masuk" :value="number_format($stats['booking_masuk'])" asset="assets/package.webp" color="rose" />
                <x-stat-card stat="laundry_processing" label="Sedang Diproses" :value="number_format($stats['laundry_processing'])" asset="assets/washing-machine.webp" color="amber" />
                <x-stat-card stat="laundry_done" label="Booking Selesai" :value="number_format($stats['laundry_done'])" asset="assets/folded-clothes.webp" color="emerald" />
                <x-stat-card stat="total_payments" label="Total Payments" :value="number_format($stats['total_payments'])" asset="assets/receipt.webp" color="indigo" />
                <x-stat-card stat="total_revenue_paid" label="Pendapatan Paid" :value="'Rp '.number_format($stats['total_revenue_paid'], 0, ',', '.')" asset="assets/wallet.webp" color="emerald" />
                <x-stat-card stat="total_receivables" label="Piutang" :value="'Rp '.number_format($stats['total_receivables'], 0, ',', '.')" asset="assets/chartpie.webp" color="rose" />
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-base font-semibold text-gray-900">5 Booking Terbaru</h3>
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
                                @forelse ($recentBookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $booking->booking_code }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->customer?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->service?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada booking.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-base font-semibold text-gray-900">5 Payment Terbaru</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full vault-dashboard-table divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($recentPayments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $payment->payment_code }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->booking?->booking_code ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm vault-nowrap text-gray-900">Rp {{ number_format($payment->total_bill, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($payment->payment_status) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada payment.</td>
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
