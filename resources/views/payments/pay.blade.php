@php
    $booking = $payment->booking;
    $isCod = $paymentChannel === 'cod';
    $confirmLabel = $isCod ? 'Konfirmasi Pesanan' : ($paymentChannel === 'transfer' ? 'Saya Sudah Transfer' : 'Saya Sudah Bayar');
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-black leading-tight text-neutral-900">
                {{ __('Pembayaran Laundry') }}
            </h2>
            <p class="mt-1 text-sm font-medium text-neutral-500">
                {{ $isCod ? 'Pesanan COD akan dibayar di tempat dan dikonfirmasi kasir.' : 'Selesaikan pembayaran sebelum konfirmasi WhatsApp.' }}
            </p>
        </div>
    </x-slot>

    <div
        class="py-6"
        x-data="{
            remaining: 15 * 60,
            tick() {
                if (this.remaining > 0) {
                    this.remaining--;
                }
            },
            timeLeft() {
                const minutes = String(Math.floor(this.remaining / 60)).padStart(2, '0');
                const seconds = String(this.remaining % 60).padStart(2, '0');
                return `${minutes}:${seconds}`;
            },
        }"
        x-init="setInterval(() => tick(), 1000)"
    >
        <div class="mx-auto max-w-5xl space-y-5 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <section class="overflow-hidden rounded-[2rem] border border-[#E8DCCB] bg-[#FFF9F1] shadow-[0_24px_65px_rgba(24,21,18,0.10)]">
                <div class="grid gap-0 lg:grid-cols-[minmax(0,1fr)_360px]">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('logo.svg') }}" alt="VAULTLAUNDRY" class="h-12 w-12 rounded-2xl">
                                <div>
                                    <div class="text-2xl font-black tracking-tight text-neutral-950">
                                        VAULT<span class="text-[#FF6626]">LAUNDRY</span>
                                    </div>
                                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-400">Mock Payment</p>
                                </div>
                            </div>

                            @include('payments._status-badge', [
                                'status' => $isCod ? 'unpaid' : 'pending_confirmation',
                                'payment' => $payment,
                            ])
                        </div>

                        <div class="mt-8 rounded-3xl border border-[#E8DCCB] bg-[#FBF3E7] p-5">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <x-card-field label="Payment Code" :value="$payment->payment_code" />
                                <x-card-field label="Booking Code" :value="$booking?->booking_code ?? '-'" />
                                <x-card-field label="Customer" :value="$booking?->customer?->name ?? '-'" />
                                <x-card-field label="Layanan" :value="$booking?->service?->name ?? '-'" />
                            </div>

                            <div class="mt-5 rounded-2xl border border-[#E8DCCB] bg-[#FFF9F1] p-5">
                                <div class="text-[0.68rem] font-black uppercase tracking-[0.14em] text-neutral-400">Total Tagihan</div>
                                <div class="mt-2 text-3xl font-black text-neutral-950">Rp {{ number_format($payment->total_bill, 0, ',', '.') }}</div>
                                <div class="mt-3 flex flex-wrap items-center gap-2 text-sm font-bold text-neutral-500">
                                    <span>Metode: {{ $paymentMethodLabel }}</span>
                                    @unless ($isCod)
                                        <span class="h-1 w-1 rounded-full bg-neutral-300"></span>
                                        <span>Sisa waktu: <span class="text-[#FF6626]" x-text="timeLeft()"></span></span>
                                    @endunless
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            @if ($isCod)
                                <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-3xl bg-[#FF6626] text-white shadow-lg shadow-orange-500/25">
                                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.5L19 9.5V19a2 2 0 0 1-2 2Z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-black text-amber-900">Bayar di Tempat</h3>
                                            <p class="mt-2 text-sm font-semibold leading-6 text-amber-700">
                                                Tidak perlu melakukan pembayaran online. Pembayaran dilakukan di tempat dan akan dikonfirmasi kasir.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($paymentChannel === 'qris')
                                <div class="rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] p-6 text-center">
                                    <div class="mx-auto grid h-56 w-56 grid-cols-7 gap-1 rounded-3xl border-8 border-white bg-white p-4 shadow-[0_18px_45px_rgba(24,21,18,0.10)]">
                                        @for ($i = 0; $i < 49; $i++)
                                            <span class="{{ in_array($i, [0,1,2,7,9,14,15,16,4,5,6,11,13,18,19,20,28,29,30,35,37,42,43,44,45,47,48,22,24,31,33,38,40]) ? 'bg-neutral-950' : 'bg-[#F4E8D8]' }} rounded-[0.18rem]"></span>
                                        @endfor
                                    </div>
                                    <h3 class="mt-6 text-lg font-black text-neutral-950">Scan QRIS untuk menyelesaikan pembayaran</h3>
                                    <p class="mt-2 text-sm font-semibold text-neutral-500">Gunakan aplikasi m-banking atau e-wallet, lalu klik tombol konfirmasi setelah pembayaran berhasil.</p>
                                </div>
                            @elseif ($paymentChannel === 'transfer')
                                <div class="rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] p-6">
                                    <div class="rounded-3xl border border-blue-100 bg-blue-50 p-5">
                                        <div class="text-[0.68rem] font-black uppercase tracking-[0.14em] text-blue-500">Transfer Bank</div>
                                        <h3 class="mt-2 text-2xl font-black text-neutral-950">Bank BCA</h3>
                                        <div class="mt-5 rounded-2xl border border-blue-100 bg-white/70 p-4">
                                            <div class="text-xs font-black uppercase tracking-[0.14em] text-neutral-400">Nomor Rekening</div>
                                            <div class="mt-1 font-mono text-3xl font-black tracking-wide text-neutral-950">1234567890</div>
                                        </div>
                                        <p class="mt-4 text-sm font-bold text-neutral-700">Atas nama: VAULTLAUNDRY</p>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] p-6">
                                    <div class="grid gap-3 sm:grid-cols-3">
                                        @foreach (['Dana', 'OVO', 'GoPay'] as $wallet)
                                            <div class="rounded-3xl border border-[#E8DCCB] bg-[#FBF3E7] p-5 text-center">
                                                <div class="text-lg font-black text-neutral-950">{{ $wallet }}</div>
                                                <div class="mt-3 font-mono text-sm font-black text-[#FF6626]">0812-0000-2026</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="mt-5 text-center text-sm font-semibold text-neutral-500">Transfer ke salah satu e-wallet atas nama VAULTLAUNDRY.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <aside class="border-t border-[#E8DCCB] bg-[#FBF3E7] p-6 lg:border-l lg:border-t-0">
                        <div class="lg:sticky lg:top-28">
                            <h3 class="text-lg font-black text-neutral-950">Konfirmasi Pembayaran</h3>
                            <p class="mt-2 text-sm font-semibold text-neutral-500">
                                {{ $isCod ? 'Konfirmasi pesanan untuk melihat instruksi COD dan status cucian.' : 'Klik konfirmasi hanya setelah pembayaran benar-benar dilakukan.' }}
                            </p>

                            @if ($isCod)
                                <a href="{{ route('user.orders.success', $booking) }}" class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-[#FF6626] px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/25 transition-colors duration-200 hover:bg-[#d94b12]">
                                    {{ $confirmLabel }}
                                </a>
                            @else
                                <form method="POST" action="{{ route('payments.confirm', $payment) }}" class="mt-6">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="payment_method" value="{{ $paymentChannel }}">

                                    <button type="submit" class="w-full rounded-2xl bg-[#FF6626] px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/25 transition-colors duration-200 hover:bg-[#d94b12]">
                                        {{ $confirmLabel }}
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('bookings.show', $booking) }}" class="mt-3 inline-flex w-full items-center justify-center rounded-2xl border border-[#E8DCCB] bg-[#FFF9F1] px-5 py-3 text-sm font-black text-neutral-800 transition-colors duration-200 hover:border-[#FF6626]/40 hover:text-[#FF6626]">
                                Bayar Nanti
                            </a>

                            <div class="mt-6 rounded-3xl border border-amber-200 bg-amber-50 p-4">
                                <div class="text-sm font-black text-amber-800">Status sementara</div>
                                <p class="mt-1 text-xs font-semibold leading-5 text-amber-700">
                                    {{ $isCod ? 'Status tetap Bayar di Tempat sampai kasir mengonfirmasi pembayaran.' : 'Status pembayaran menunggu konfirmasi sampai tombol pembayaran ditekan.' }}
                                </p>
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
