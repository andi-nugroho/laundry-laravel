<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard Statistik
                </h2>
                <p class="mt-1 text-sm text-gray-500">Ringkasan operasional laundry — Admin</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 ring-1 ring-indigo-100">
                Admin
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <x-stat-card label="Total Booking" :value="number_format($stats['total_booking'])" color="indigo" />
                <x-stat-card label="Total Transaksi" :value="number_format($stats['total_transaksi'])" color="sky" />
                <x-stat-card label="Total Pendapatan" :value="'Rp '.number_format($stats['total_pendapatan'], 0, ',', '.')" color="emerald" />
                <x-stat-card label="Laundry Diproses" :value="number_format($stats['laundry_proses'])" color="amber" />
                <x-stat-card label="Laundry Selesai" :value="number_format($stats['laundry_selesai'])" color="emerald" />
                <x-stat-card label="Belum Dibayar" :value="number_format($stats['belum_dibayar'])" color="rose" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 p-6">
                    <h3 class="text-base font-semibold text-gray-900">Grafik Pendapatan</h3>
                    <p class="mt-1 text-sm text-gray-500">Akan ditampilkan setelah modul transaksi tersedia.</p>
                    <div class="mt-6 flex h-40 items-center justify-center rounded-lg bg-gray-50 border border-dashed border-gray-200">
                        <span class="text-sm text-gray-400">Chart placeholder</span>
                    </div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 p-6">
                    <h3 class="text-base font-semibold text-gray-900">Laporan Terbaru</h3>
                    <p class="mt-1 text-sm text-gray-500">Akan ditampilkan setelah modul laporan tersedia.</p>
                    <div class="mt-6 flex h-40 items-center justify-center rounded-lg bg-gray-50 border border-dashed border-gray-200">
                        <span class="text-sm text-gray-400">Belum ada data</span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Booking Terbaru</h3>
                </div>
                <div class="px-6 py-12 text-center text-sm text-gray-500">
                    Belum ada data booking. Fitur akan aktif pada tahap booking laundry.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
