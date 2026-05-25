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

    <div>
        <x-input-label for="service_id" value="Layanan" />
        <select id="service_id" name="service_id" required
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Pilih layanan</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}" @selected((string) old('service_id', $booking?->service_id) === (string) $service->id)>
                    {{ $service->name }} - Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg ({{ $service->estimated_days }} hari)
                </option>
            @endforeach
        </select>
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
