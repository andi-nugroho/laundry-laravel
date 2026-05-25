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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card label="Booking Masuk Hari Ini" :value="number_format($stats['booking_hari_ini'])" color="sky" />
                <x-stat-card label="Laundry Sedang Diproses" :value="number_format($stats['laundry_proses'])" color="amber" />
                <x-stat-card label="Transaksi Hari Ini" :value="number_format($stats['transaksi_hari_ini'])" color="indigo" />
                <x-stat-card label="Cucian Siap Diambil" :value="number_format($stats['siap_diambil'])" color="emerald" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900">Antrian Operasional</h3>
                        <span class="text-xs text-gray-400">Hari ini</span>
                    </div>
                    <div class="px-6 py-12 text-center text-sm text-gray-500">
                        Belum ada antrian. Fitur monitoring akan aktif pada tahap berikutnya.
                    </div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 p-6">
                    <h3 class="text-base font-semibold text-gray-900">Aksi Cepat</h3>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2 rounded-lg bg-gray-50 px-3 py-2">
                            <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                            Input booking baru
                        </li>
                        <li class="flex items-center gap-2 rounded-lg bg-gray-50 px-3 py-2">
                            <span class="h-2 w-2 rounded-full bg-sky-400"></span>
                            Update status cucian
                        </li>
                        <li class="flex items-center gap-2 rounded-lg bg-gray-50 px-3 py-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            Cetak nota transaksi
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Riwayat Transaksi Terbaru</h3>
                </div>
                <div class="px-6 py-12 text-center text-sm text-gray-500">
                    Belum ada transaksi. Fitur akan aktif pada tahap transaksi & pembayaran.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
