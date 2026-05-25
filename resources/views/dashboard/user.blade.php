<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard Pelanggan
                </h2>
                <p class="mt-1 text-sm text-gray-500">Pantau booking dan status cucian Anda</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-medium text-sky-700 ring-1 ring-sky-100">
                Pelanggan
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card label="Booking Aktif" :value="number_format($stats['booking_aktif'])" color="indigo" />
                <x-stat-card label="Status Cucian" :value="$stats['status_cucian']" color="amber" />
                <x-stat-card label="Riwayat Booking" :value="number_format($stats['riwayat_booking'])" color="slate" />
                <x-stat-card label="Estimasi Selesai" :value="$stats['estimasi_selesai']" color="emerald" />
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900">Status Cucian</h3>
                <p class="mt-1 text-sm text-gray-500">Timeline pelacakan status akan tersedia setelah modul booking aktif.</p>
                <div class="mt-6 flex flex-col sm:flex-row gap-4">
                    @foreach (['Diterima', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai'] as $step)
                        <div class="flex-1 rounded-lg border border-dashed border-gray-200 bg-gray-50 px-4 py-3 text-center">
                            <span class="text-xs font-medium text-gray-400">{{ $step }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl ring-1 ring-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Riwayat Booking</h3>
                </div>
                <div class="px-6 py-12 text-center text-sm text-gray-500">
                    Anda belum memiliki riwayat booking. Mulai booking laundry setelah fitur tersedia.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
