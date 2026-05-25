@php
    $payment = $payment ?? null;
    $selectedBookingId = old('booking_id', $payment?->booking_id ?? $selectedBookingId ?? null);
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="booking_id" value="Booking" />
        <select id="booking_id" name="booking_id" required
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Pilih booking</option>
            @foreach ($bookings as $booking)
                <option value="{{ $booking->id }}" @selected((string) $selectedBookingId === (string) $booking->id)>
                    {{ $booking->booking_code }} - {{ $booking->customer?->name ?? 'Tanpa pelanggan' }} - {{ $booking->service?->name ?? 'Tanpa layanan' }} - Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('booking_id')" />
        <p class="mt-1 text-xs text-gray-500">Total tagihan diambil otomatis dari total booking.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="payment_date" value="Tanggal Pembayaran" />
            <x-text-input id="payment_date" name="payment_date" type="datetime-local" class="mt-1 block w-full"
                :value="old('payment_date', $payment?->payment_date?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i'))" required />
            <x-input-error class="mt-2" :messages="$errors->get('payment_date')" />
        </div>

        <div>
            <x-input-label for="payment_method" value="Metode Pembayaran" />
            <select id="payment_method" name="payment_method" required
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih metode</option>
                <option value="cash" @selected(old('payment_method', $payment?->payment_method) === 'cash')>Cash</option>
                <option value="transfer" @selected(old('payment_method', $payment?->payment_method) === 'transfer')>Transfer</option>
                <option value="ewallet" @selected(old('payment_method', $payment?->payment_method) === 'ewallet')>E-wallet</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('payment_method')" />
        </div>
    </div>

    <div>
        <x-input-label for="amount_paid" value="Nominal Dibayar (Rp)" />
        <x-text-input id="amount_paid" name="amount_paid" type="number" step="0.01" min="0"
            class="mt-1 block w-full" :value="old('amount_paid', $payment?->amount_paid)" required />
        <x-input-error class="mt-2" :messages="$errors->get('amount_paid')" />
    </div>

    <div>
        <x-input-label for="notes" value="Catatan" />
        <textarea id="notes" name="notes" rows="3"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            placeholder="Opsional">{{ old('notes', $payment?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
