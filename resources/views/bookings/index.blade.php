<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ request()->routeIs('monitoring.*') ? __('Monitoring Laundry') : __('Booking Laundry') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ Auth::user()->isUser() ? 'Pantau status laundry Anda' : 'Kelola booking dan status laundry pelanggan' }}
                </p>
            </div>

            @can('create', \App\Models\Booking::class)
                <a href="{{ route('bookings.create') }}">
                    <x-primary-button type="button">
                        + Tambah Booking
                    </x-primary-button>
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700 ring-1 ring-green-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pickup</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->booking_code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $booking->customer?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $booking->service?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div>{{ $booking->booking_date?->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-400">Est. {{ $booking->estimated_finish_date?->format('d M Y') ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $booking->weight ? number_format($booking->weight, 2, ',', '.') . ' kg' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ str_replace('_', ' ', ucfirst($booking->pickup_type)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-2">
                                            @include('bookings._status-badge', ['status' => $booking->status])
                                            @include('bookings._status-form', ['booking' => $booking, 'class' => 'flex items-center gap-2'])
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        @can('view', $booking)
                                            <a href="{{ route('bookings.show', $booking) }}" class="text-gray-600 hover:text-gray-900">Detail</a>
                                        @endcan

                                        @can('update', $booking)
                                            <a href="{{ route('bookings.edit', $booking) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        @endcan

                                        @can('delete', $booking)
                                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus booking ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-sm text-gray-500">
                                        Belum ada booking laundry.
                                        @can('create', \App\Models\Booking::class)
                                            <a href="{{ route('bookings.create') }}" class="text-indigo-600 hover:underline">Tambah booking pertama</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($bookings->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
