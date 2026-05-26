<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black leading-tight text-neutral-900">
            {{ __('Simulasi Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-3xl">
                <div class="p-8 sm:p-12">
                    {{-- Logo --}}
                    <div class="flex justify-center mb-8">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#FF6626] to-[#ff8c5a] text-white shadow-lg shadow-orange-500/30">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <span class="text-2xl font-black tracking-tight text-neutral-900">
                                VAULT<span class="text-[#FF6626]">LAUNDRY</span>
                            </span>
                        </div>
                    </div>

                    <div class="text-center mb-10">
                        <h3 class="text-sm font-bold tracking-widest uppercase text-neutral-400">Total Tagihan</h3>
                        <p class="text-4xl font-black text-neutral-900 mt-2">Rp {{ number_format($payment->total_bill, 0, ',', '.') }}</p>
                        <p class="text-sm font-medium text-neutral-500 mt-2">Order: {{ $payment->booking->booking_code }}</p>
                    </div>

                    <div class="grid gap-4 mb-10 text-sm bg-[#FFF9F1] p-6 rounded-2xl border border-[#E8DCCB]">
                        <div class="flex justify-between">
                            <span class="font-medium text-neutral-500">Payment Code</span>
                            <span class="font-bold text-neutral-900">{{ $payment->payment_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-neutral-500">Pelanggan</span>
                            <span class="font-bold text-neutral-900">{{ $payment->booking->customer->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-neutral-500">Layanan</span>
                            <span class="font-bold text-neutral-900">{{ $payment->booking->service->name ?? '-' }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('payments.confirm', $payment) }}" x-data="{ method: 'qris' }">
                        @csrf
                        @method('PATCH')
                        
                        <h4 class="text-base font-black text-neutral-900 mb-4">Pilih Metode Pembayaran</h4>
                        
                        <div class="grid grid-cols-3 gap-3 mb-8">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="qris" x-model="method" class="peer sr-only">
                                <div class="rounded-xl border-2 border-gray-200 p-4 text-center transition peer-checked:border-[#FF6626] peer-checked:bg-orange-50">
                                    <div class="text-2xl mb-1">📱</div>
                                    <div class="text-sm font-bold text-neutral-700 peer-checked:text-[#FF6626]">QRIS</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="transfer" x-model="method" class="peer sr-only">
                                <div class="rounded-xl border-2 border-gray-200 p-4 text-center transition peer-checked:border-[#FF6626] peer-checked:bg-orange-50">
                                    <div class="text-2xl mb-1">🏦</div>
                                    <div class="text-sm font-bold text-neutral-700 peer-checked:text-[#FF6626]">Transfer</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="ewallet" x-model="method" class="peer sr-only">
                                <div class="rounded-xl border-2 border-gray-200 p-4 text-center transition peer-checked:border-[#FF6626] peer-checked:bg-orange-50">
                                    <div class="text-2xl mb-1">💳</div>
                                    <div class="text-sm font-bold text-neutral-700 peer-checked:text-[#FF6626]">E-Wallet</div>
                                </div>
                            </label>
                        </div>

                        {{-- Instructions --}}
                        <div class="mb-8 rounded-2xl bg-gray-50 p-6 text-center border border-gray-100">
                            <div x-show="method === 'qris'">
                                <div class="w-48 h-48 bg-white border-2 border-dashed border-gray-300 rounded-xl mx-auto flex items-center justify-center mb-4">
                                    <span class="text-gray-400 font-black text-xl">DUMMY QR</span>
                                </div>
                                <p class="text-sm font-bold text-neutral-900">VAULTLAUNDRY QRIS</p>
                                <p class="text-xs text-neutral-500 mt-1">Scan QR code di atas menggunakan aplikasi e-wallet atau m-banking Anda.</p>
                            </div>
                            
                            <div x-show="method === 'transfer'" style="display: none;">
                                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full mx-auto flex items-center justify-center mb-4 text-2xl">🏦</div>
                                <p class="text-sm font-medium text-neutral-500">Bank BCA</p>
                                <p class="text-2xl font-black text-neutral-900 mt-1 tracking-wider">1234 5678 90</p>
                                <p class="text-sm font-bold text-neutral-700 mt-2">a.n. VAULTLAUNDRY</p>
                            </div>
                            
                            <div x-show="method === 'ewallet'" style="display: none;">
                                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full mx-auto flex items-center justify-center mb-4 text-2xl">📱</div>
                                <p class="text-sm font-medium text-neutral-500">OVO / GoPay / Dana</p>
                                <p class="text-2xl font-black text-neutral-900 mt-1 tracking-wider">0812-0000-2026</p>
                                <p class="text-sm font-bold text-neutral-700 mt-2">a.n. VAULTLAUNDRY</p>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-sm font-black text-white bg-[#FF6626] hover:bg-[#e55c22] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6626] transition-colors">
                            Saya Sudah Bayar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
