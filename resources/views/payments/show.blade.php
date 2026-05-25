<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Pembayaran') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ $payment->payment_code }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('payments.index') }}">
                    <x-secondary-button type="button">Kembali</x-secondary-button>
                </a>

                @can('update', $payment)
                    <a href="{{ route('payments.edit', $payment) }}">
                        <x-primary-button type="button">Edit</x-primary-button>
                    </a>
                @endcan

                <a href="{{ route('payments.invoice', $payment) }}">
                    <x-primary-button type="button">Cetak Nota</x-primary-button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700 ring-1 ring-green-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Kode Pembayaran</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $payment->payment_code }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Booking</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $payment->booking?->booking_code ?? '-' }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Pelanggan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $payment->booking?->customer?->name ?? '-' }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Layanan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $payment->booking?->service?->name ?? '-' }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Total Tagihan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">Rp {{ number_format($payment->total_bill, 0, ',', '.') }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Dibayar</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Kembalian</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">Rp {{ number_format($payment->change_amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Metode</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 capitalize">{{ $payment->payment_method }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 sm:col-span-2 sm:mt-0">
                            @include('payments._status-badge', ['status' => $payment->payment_status])
                        </dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Petugas</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $payment->processedBy?->name ?? '-' }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Pembayaran</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $payment->payment_date?->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $payment->notes ?? '-' }}</dd>
                    </div>
                </dl>

                @can('delete', $payment)
                    <div class="border-t border-gray-100 px-4 py-5 sm:px-8">
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus pembayaran ini?');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>Hapus Pembayaran</x-danger-button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
