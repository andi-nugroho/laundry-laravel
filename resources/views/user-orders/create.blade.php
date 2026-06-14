@php
    $servicePayload = $services->map(fn ($service) => [
        'id' => $service->id,
        'name' => $service->name,
        'description' => $service->description,
        'price' => (float) $service->price_per_kg,
        'priceLabel' => 'Rp '.number_format($service->price_per_kg, 0, ',', '.'),
        'estimatedDays' => $service->estimated_days,
    ])->values();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-black leading-tight text-neutral-900">
                {{ __('Pesan Laundry') }}
            </h2>
            <p class="mt-1 text-sm font-medium text-neutral-500">Pilih layanan, isi berat laundry, lalu checkout seperti aplikasi customer.</p>
        </div>
    </x-slot>

    <div
        class="py-6"
        x-data="{
            services: @js($servicePayload),
            selected: null,
            weight: '{{ old('weight', '1') }}',
            pickupType: '{{ old('pickup_type', \App\Models\Booking::PICKUP_ANTAR_SENDIRI) }}',
            paymentOption: '{{ old('payment_option', 'cod') }}',
            notes: @js(old('notes', '')),
            selectService(service) {
                this.selected = service;
            },
            total() {
                const qty = Number(this.weight || 0);
                return this.selected ? qty * Number(this.selected.price) : 0;
            },
            totalLabel() {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(this.total());
            },
        }"
        x-init="
            const oldService = '{{ old('service_id') }}';
            selected = services.find(service => String(service.id) === oldService) || services[0] || null;
        "
    >
        <div class="mx-auto grid max-w-7xl gap-6 sm:px-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:px-8">
            <div class="space-y-5">
                @if (session('success') || session('payment_success'))
                    <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
                        {{ session('success') }}
                        @if (session('payment_success'))
                            <span class="ml-1">{{ session('payment_success') }}</span>
                        @endif
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
                        Periksa kembali pilihan layanan, berat, dan metode pembayaran.
                    </div>
                @endif

                <div class="rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] p-5 shadow-[0_18px_45px_rgba(24,21,18,0.08)] sm:p-6">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div class="text-[0.68rem] font-black uppercase tracking-[0.14em] text-[#FF6626]">Laundry Services</div>
                            <h3 class="mt-1 text-lg font-black text-neutral-950">Pilih Layanan</h3>
                        </div>
                        <p class="text-sm font-medium text-neutral-500">Satu checkout menyimpan satu layanan utama.</p>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @forelse ($services as $service)
                            <article
                                class="group rounded-3xl border p-4 shadow-[0_14px_34px_rgba(24,21,18,0.06)] transition-[border-color,background-color] duration-200"
                                :class="selected && selected.id === {{ $service->id }} ? 'border-[#FF6626] bg-[#FFF3E8]' : 'border-[#E8DCCB] bg-[#FFF9F1]'"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[#E8DCCB] bg-[#FBF3E7] p-2 shadow-sm">
                                        @include('services._icon', ['name' => $service->name, 'class' => 'h-10 w-10'])
                                    </div>
                                    <span class="rounded-full bg-[#FBF3E7] px-2.5 py-1 text-xs font-black text-neutral-600">{{ $service->estimated_days }} hari</span>
                                </div>

                                <h4 class="mt-4 text-base font-black text-neutral-950">{{ $service->name }}</h4>
                                <p class="mt-2 min-h-10 text-sm font-medium text-neutral-500">{{ $service->description ?: 'Layanan laundry siap diproses.' }}</p>

                                <div class="mt-4 flex items-end justify-between gap-3">
                                    <div>
                                        <div class="text-[0.68rem] font-black uppercase tracking-[0.12em] text-neutral-400">Harga/Kg</div>
                                        <div class="mt-1 text-lg font-black text-neutral-950">Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}</div>
                                    </div>
                                    <button type="button" class="vault-action-primary" @click="selectService(services.find(service => service.id === {{ $service->id }}))">
                                        Tambah
                                    </button>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-[#E8DCCB] p-8 text-center text-sm font-semibold text-neutral-500 md:col-span-2 xl:col-span-3">
                                Belum ada layanan aktif.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="lg:sticky lg:top-24 lg:self-start">
                <form method="POST" action="{{ route('user.orders.checkout') }}" class="rounded-3xl border border-[#E8DCCB] bg-[#FFF9F1] p-5 shadow-[0_18px_45px_rgba(24,21,18,0.10)]">
                    @csrf
                    <input type="hidden" name="service_id" :value="selected ? selected.id : ''">

                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="text-[0.68rem] font-black uppercase tracking-[0.14em] text-[#FF6626]">Cart</div>
                            <h3 class="mt-1 text-lg font-black text-neutral-950">Keranjang</h3>
                        </div>
                        <span class="rounded-full bg-[#FBF3E7] px-2.5 py-1 text-xs font-black text-neutral-600">1 layanan</span>
                    </div>

                    <div class="mt-5 rounded-2xl border border-[#E8DCCB] bg-[#FBF3E7] p-4" x-show="selected">
                        <div class="text-sm font-black text-neutral-950" x-text="selected?.name"></div>
                        <div class="mt-1 text-xs font-semibold text-neutral-500">
                            <span x-text="selected?.priceLabel"></span>/kg - estimasi <span x-text="selected?.estimatedDays"></span> hari
                        </div>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div>
                            <x-input-label for="weight" value="Berat / Quantity (kg)" />
                            <x-text-input id="weight" name="weight" type="number" step="0.1" min="0.1" class="mt-1 block w-full" x-model="weight" required />
                            <x-input-error class="mt-2" :messages="$errors->get('weight')" />
                        </div>

                        <div>
                            <x-input-label for="pickup_type" value="Tipe Pengambilan" />
                            <select id="pickup_type" name="pickup_type" class="mt-1 block w-full" x-model="pickupType" required>
                                <option value="antar_sendiri">Antar Sendiri</option>
                                <option value="pickup">Pickup</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('pickup_type')" />
                        </div>

                        <div>
                            <x-input-label value="Metode Pembayaran" />
                            <div class="mt-2 grid gap-2">
                                @foreach ([
                                    'qris' => 'QRIS',
                                    'transfer' => 'Transfer',
                                    'ewallet' => 'E-Wallet',
                                    'cod' => 'COD / Bayar di Tempat',
                                ] as $value => $label)
                                    <label class="flex cursor-pointer items-center justify-between rounded-2xl border border-[#E8DCCB] bg-[#FBF3E7] px-3 py-3 text-sm font-bold text-neutral-700 transition hover:border-[#FF6626]/40">
                                        <span>{{ $label }}</span>
                                        <input type="radio" name="payment_option" value="{{ $value }}" x-model="paymentOption" class="border-[#E8DCCB] text-[#FF6626] focus:ring-[#FF6626]">
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('payment_option')" />
                        </div>

                        <div>
                            <x-input-label for="notes" value="Catatan" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full" x-model="notes" placeholder="Opsional"></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl border border-[#E8DCCB] bg-[#FBF3E7] p-4">
                        <div class="flex items-center justify-between text-sm font-semibold text-neutral-500">
                            <span>Total estimasi</span>
                            <span class="text-xl font-black text-neutral-950" x-text="totalLabel()"></span>
                        </div>
                        <p class="mt-2 text-xs font-medium text-neutral-500" x-show="paymentOption !== 'cod'">
                            Setelah checkout, Anda akan diarahkan ke halaman instruksi pembayaran.
                        </p>
                        <p class="mt-2 text-xs font-medium text-neutral-500" x-show="paymentOption === 'cod'">
                            COD akan tercatat sebagai unpaid sampai dikonfirmasi kasir.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="mt-5 w-full rounded-2xl bg-[#FF6626] px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/25 transition-colors duration-200 hover:bg-[#d94b12] disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="! selected || Number(weight || 0) <= 0"
                        x-text="paymentOption === 'cod' ? 'Checkout COD' : 'Lanjut ke Pembayaran'"
                    ></button>
                </form>
            </aside>
        </div>
    </div>
</x-app-layout>
