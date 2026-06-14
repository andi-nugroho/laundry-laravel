<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard Pelanggan
                </h2>
                <p class="mt-1 text-sm text-gray-500">Pantau booking dan pembayaran laundry Anda</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-medium text-sky-700 ring-1 ring-sky-100">
                Pelanggan
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8" data-dashboard-realtime x-data="dashboardRealtime()" x-init="init()">
            <x-dashboard-realtime-bar />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card stat="total_bookings" label="Total Booking Saya" :value="number_format($stats['total_bookings'])" asset="assets/calendar.webp" color="indigo" />
                <x-stat-card stat="active_bookings" label="Booking Aktif" :value="number_format($stats['active_bookings'])" asset="assets/washing-machine.webp" color="amber" />
                <x-stat-card stat="done_bookings" label="Booking Selesai" :value="number_format($stats['done_bookings'])" asset="assets/folded-clothes.webp" color="emerald" />
                <x-stat-card stat="paid_payments_total" label="Pembayaran Paid" :value="'Rp '.number_format($stats['paid_payments_total'], 0, ',', '.')" asset="assets/wallet.webp" color="sky" />
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">5 Booking Terbaru Saya</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full vault-dashboard-table divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($recentBookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $booking->booking_code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->service?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->booking_date?->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->payment ? ucfirst($booking->payment->payment_status) : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Anda belum memiliki booking.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
