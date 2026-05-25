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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <x-stat-card label="Total Customers" :value="number_format($stats['total_customers'])" color="indigo" />
                <x-stat-card label="Services Aktif" :value="number_format($stats['total_services_active'])" color="sky" />
                <x-stat-card label="Total Bookings" :value="number_format($stats['total_bookings'])" color="slate" />
                <x-stat-card label="Bookings Hari Ini" :value="number_format($stats['total_bookings_today'])" color="amber" />
                <x-stat-card label="Booking Masuk" :value="number_format($stats['booking_masuk'])" color="rose" />
                <x-stat-card label="Sedang Diproses" :value="number_format($stats['laundry_processing'])" color="amber" />
                <x-stat-card label="Booking Selesai" :value="number_format($stats['laundry_done'])" color="emerald" />
                <x-stat-card label="Total Payments" :value="number_format($stats['total_payments'])" color="indigo" />
                <x-stat-card label="Pendapatan Paid" :value="'Rp '.number_format($stats['total_revenue_paid'], 0, ',', '.')" color="emerald" />
                <x-stat-card label="Piutang" :value="'Rp '.number_format($stats['total_receivables'], 0, ',', '.')" color="rose" />
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-base font-semibold text-gray-900">5 Booking Terbaru</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
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
                        <table class="min-w-full divide-y divide-gray-200">
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
                                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($payment->total_bill, 0, ',', '.') }}</td>
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
