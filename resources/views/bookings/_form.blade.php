@php
    $booking = $booking ?? null;
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="customer_id" value="Pelanggan" />
        <select id="customer_id" name="customer_id"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Tanpa data pelanggan</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" @selected((string) old('customer_id', $booking?->customer_id) === (string) $customer->id)>
                    {{ $customer->name }}{{ $customer->phone ? ' - '.$customer->phone : '' }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('customer_id')" />
    </div>

    <div x-data="{ selectedServiceId: '{{ old('service_id', $booking?->service_id) }}' }">
        <x-input-label value="Layanan" />
        <input type="hidden" name="service_id" x-model="selectedServiceId">

        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($services as $service)
                <label class="cursor-pointer relative block h-full">
                    <input type="radio" value="{{ $service->id }}" x-model="selectedServiceId" class="peer sr-only">
                    <div class="flex h-full flex-col rounded-2xl border-2 border-gray-200 bg-white p-5 transition-[border-color,background-color,box-shadow] duration-200 hover:border-gray-300 peer-checked:border-[#FF6626] peer-checked:bg-orange-50/50 peer-checked:shadow-sm">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl text-2xl transition-colors"
                                 :class="selectedServiceId == '{{ $service->id }}' ? 'bg-[#FF6626] text-white shadow-lg shadow-orange-500/30' : 'bg-gray-100 text-gray-500'">
                                @php
                                    $icon = match(strtolower($service->name)) {
                                        'cuci kering' => '🧺',
                                        'cuci setrika' => '👔',
                                        'setrika saja' => '✨',
                                        'laundry express' => '⚡',
                                        'laundry sepatu' => '👟',
                                        'laundry bedcover' => '🛏️',
                                        default => '🫧'
                                    };
                                @endphp
                                {{ $icon }}
                            </div>
                            @if ($service->is_active)
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-black text-emerald-700">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-black text-red-700">Tidak Aktif</span>
                            @endif
                        </div>
                        
                        <div class="mt-4 flex-grow">
                            <h4 class="text-base font-black text-neutral-900">{{ $service->name }}</h4>
                            @if($service->description)
                                <p class="mt-1 text-sm font-medium text-neutral-500 line-clamp-2">{{ $service->description }}</p>
                            @endif
                        </div>
                        
                        <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">
                            <div>
                                <span class="block text-[10px] font-black uppercase tracking-widest text-neutral-400">Harga</span>
                                <p class="mt-0.5 text-sm font-black text-[#FF6626]">Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}<span class="text-xs font-medium text-neutral-500">/kg</span></p>
                            </div>
                            <div class="text-right">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-neutral-400">Estimasi</span>
                                <p class="mt-0.5 text-sm font-bold text-neutral-700">{{ $service->estimated_days }} Hari</p>
                            </div>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('service_id')" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="booking_date" value="Tanggal Booking" />
            <x-text-input id="booking_date" name="booking_date" type="date" class="mt-1 block w-full"
                :value="old('booking_date', $booking?->booking_date?->format('Y-m-d') ?? now()->format('Y-m-d'))" required />
            <x-input-error class="mt-2" :messages="$errors->get('booking_date')" />
        </div>

        <div>
            <x-input-label for="weight" value="Berat (kg)" />
            <x-text-input id="weight" name="weight" type="number" step="0.01" min="0"
                class="mt-1 block w-full" :value="old('weight', $booking?->weight)" />
            <x-input-error class="mt-2" :messages="$errors->get('weight')" />
        </div>
    </div>

    <div>
        <x-input-label for="pickup_type" value="Tipe Pengambilan" />
        <select id="pickup_type" name="pickup_type"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="antar_sendiri" @selected(old('pickup_type', $booking?->pickup_type ?? 'antar_sendiri') === 'antar_sendiri')>Antar Sendiri</option>
            <option value="pickup" @selected(old('pickup_type', $booking?->pickup_type) === 'pickup')>Pickup</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('pickup_type')" />
    </div>

    <div>
        <x-input-label for="notes" value="Catatan" />
        <textarea id="notes" name="notes" rows="3"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            placeholder="Opsional">{{ old('notes', $booking?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
