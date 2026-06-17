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

            <div class="rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] p-4 shadow-[0_18px_45px_rgba(24,21,18,0.08)] sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-black text-neutral-950">Grafik Pendapatan</h3>
                        <p class="mt-1 text-sm font-medium text-neutral-500">Ringkasan pendapatan lunas dari seluruh transaksi</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['7d' => '7 Hari', '1m' => '1 Bulan', '1y' => '1 Tahun'] as $range => $label)
                            <a
                                href="{{ route('dashboard.admin', ['chart_range' => $range]) }}"
                                class="inline-flex items-center rounded-full px-4 py-2 text-xs font-black transition {{ $chartRange === $range ? 'bg-[#FF6626] text-white shadow-lg shadow-orange-500/20' : 'border border-[#E8DCCB] bg-[#FFF9F1] text-neutral-700 hover:border-[#FF6626]/40 hover:text-[#FF6626]' }}"
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-[#E8DCCB] bg-[#FBF3E7] p-4">
                        <div class="text-[0.68rem] font-black uppercase tracking-[0.12em] text-neutral-400">Total Pendapatan</div>
                        <div class="mt-1 text-2xl font-black text-neutral-950">Rp {{ number_format($chart['total_spent'], 0, ',', '.') }}</div>
                    </div>
                    <div class="rounded-2xl border border-[#E8DCCB] bg-[#FBF3E7] p-4">
                        <div class="text-[0.68rem] font-black uppercase tracking-[0.12em] text-neutral-400">Transaksi Lunas</div>
                        <div class="mt-1 text-2xl font-black text-neutral-950">{{ number_format($chart['paid_count']) }}</div>
                    </div>
                    <div class="rounded-2xl border border-[#E8DCCB] bg-[#FBF3E7] p-4">
                        <div class="text-[0.68rem] font-black uppercase tracking-[0.12em] text-neutral-400">Rata-rata Pendapatan</div>
                        <div class="mt-1 text-2xl font-black text-neutral-950">Rp {{ number_format($chart['average_spent'], 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="mt-6 h-72 sm:h-80">
                    <canvas id="adminRevenueChart" aria-label="Grafik pendapatan" role="img"></canvas>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('adminRevenueChart');
            if (! canvas || typeof Chart === 'undefined') {
                return;
            }

            const labels = @json($chart['labels']);
            const values = @json($chart['values']);

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: values,
                        backgroundColor: 'rgba(255, 102, 38, 0.82)',
                        borderColor: '#FF6626',
                        borderWidth: 1,
                        borderRadius: 10,
                        maxBarThickness: 42,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    const value = context.parsed.y ?? 0;
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                },
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                                },
                            },
                        },
                    },
                },
            });
        });
    </script>
</x-app-layout>
