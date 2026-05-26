@php
    $payment = $booking->payment;
    $waMessage = rawurlencode("Halo VAULTLAUNDRY, saya ingin konfirmasi pesanan {$booking->booking_code} dengan total Rp ".number_format($booking->total_price, 0, ',', '.').'.');
    $waUrl = "https://wa.me/6285316065960?text={$waMessage}";
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-black leading-tight text-neutral-900">
                {{ __('Pesanan Diterima') }}
            </h2>
            <p class="mt-1 text-sm font-medium text-neutral-500">Terima kasih, pesanan laundry Anda sudah masuk ke sistem.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl space-y-5 sm:px-6 lg:px-8">
            @if (session('success') || session('payment_success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                    @if (session('payment_success'))
                        <span class="ml-1">{{ session('payment_success') }}</span>
                    @endif
                </div>
            @endif

            <section class="overflow-hidden rounded-[2rem] border border-[#E8DCCB] bg-[#FFF9F1] shadow-[0_24px_65px_rgba(24,21,18,0.10)]">
                <div class="bg-[#FBF3E7] px-6 py-8 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[#FF6626] text-white shadow-xl shadow-orange-500/25">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-3xl font-black tracking-tight text-neutral-950">Pesanan Diterima!</h3>
                    <p class="mt-2 text-sm font-medium text-neutral-500">Kode booking Anda:</p>
                    <div class="mt-3 inline-flex rounded-2xl border border-[#E8DCCB] bg-[#FFF9F1] px-5 py-3 text-lg font-black text-[#FF6626]">
                        {{ $booking->booking_code }}
                    </div>
                </div>

                <div class="grid gap-4 p-6 sm:grid-cols-2">
                    <x-card-field label="Layanan" :value="$booking->service?->name ?? '-'" />
                    <x-card-field label="Customer" :value="$booking->customer?->name ?? '-'" />
                    <x-card-field label="Berat" :value="$booking->weight ? number_format($booking->weight, 2, ',', '.') . ' kg' : '-'" />
                    <x-card-field label="Estimasi Selesai" :value="$booking->estimated_finish_date?->format('d M Y') ?? '-'" />
                    <x-card-field label="Total" :value="'Rp '.number_format($booking->total_price, 0, ',', '.')" />
                    <x-card-field label="Status Pembayaran" :value="$payment ? ucfirst($payment->payment_status) : '-'" />
                </div>

                <div class="border-t border-[#E8DCCB] p-6">
                    @if ($payment?->payment_status === \App\Models\Payment::STATUS_PAID)
                        <div class="rounded-3xl border border-green-200 bg-green-50 p-5">
                            <h4 class="text-base font-black text-green-800">Pembayaran berhasil</h4>
                            <p class="mt-2 text-sm font-semibold text-green-700">
                                Payment {{ $payment->payment_code }} tercatat paid. Tim VAULTLAUNDRY akan memproses pesanan Anda.
                            </p>
                        </div>
                    @else
                        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5">
                            <h4 class="text-base font-black text-amber-800">COD / Bayar di Tempat</h4>
                            <p class="mt-2 text-sm font-semibold text-amber-700">
                                Pembayaran tercatat unpaid dan akan dikonfirmasi saat laundry diterima atau diambil.
                            </p>
                        </div>
                    @endif

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex flex-1 items-center justify-center rounded-2xl bg-[#25D366] px-5 py-3 text-sm font-black text-white shadow-lg shadow-green-500/20 transition hover:-translate-y-0.5 hover:bg-[#1fb85a]">
                            Konfirmasi via WhatsApp
                        </a>
                        <a href="{{ route('user.status-cucian') }}" class="inline-flex flex-1 items-center justify-center rounded-2xl border border-[#E8DCCB] bg-[#FFF9F1] px-5 py-3 text-sm font-black text-neutral-800 transition hover:-translate-y-0.5 hover:border-[#FF6626]/40 hover:text-[#FF6626]">
                            Lihat Status Cucian
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
