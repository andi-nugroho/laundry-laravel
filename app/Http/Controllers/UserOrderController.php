<?php

namespace App\Http\Controllers;

use App\Support\DashboardBroadcast;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserOrderController extends Controller
{
    public function create(): View
    {
        return view('user-orders.create', [
            'services' => Service::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'service_id' => ['required', 'integer', Rule::exists('services', 'id')->where('is_active', true)],
            'weight' => ['required', 'numeric', 'min:0.1', 'max:999999.99'],
            'pickup_type' => ['required', Rule::in(Booking::PICKUP_TYPES)],
            'payment_option' => ['required', Rule::in(['qris', 'transfer', 'ewallet', 'cod'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        $service = Service::findOrFail($data['service_id']);
        $bookingDate = today();
        $totalPrice = (float) $data['weight'] * (float) $service->price_per_kg;
        $customer = $this->customerForUser($request);

        $booking = Booking::create([
            'booking_code' => $this->generateBookingCode($bookingDate->toDateString()),
            'user_id' => $user->id,
            'customer_id' => $customer?->id,
            'service_id' => $service->id,
            'booking_date' => $bookingDate,
            'estimated_finish_date' => $bookingDate->copy()->addDays($service->estimated_days),
            'weight' => $data['weight'],
            'total_price' => $totalPrice,
            'pickup_type' => $data['pickup_type'],
            'status' => Booking::STATUS_BOOKING_MASUK,
            'notes' => $data['notes'] ?? null,
        ]);
        DashboardBroadcast::bookingChanged($booking);

        $payment = $this->createPaymentForOrder($booking, $data['payment_option'], $totalPrice);
        DashboardBroadcast::paymentChanged($payment);

        return redirect()
            ->route('user.orders.success', $booking)
            ->with('success', 'Pesanan berhasil dibuat')
            ->with('payment_success', $data['payment_option'] === 'cod' ? null : 'Pembayaran berhasil');
    }

    public function success(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        return view('user-orders.success', [
            'booking' => $booking->load(['customer', 'service', 'payment']),
        ]);
    }

    private function customerForUser(Request $request): ?Customer
    {
        $user = $request->user();

        return Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'phone' => null,
                'address' => null,
                'notes' => 'Dibuat otomatis dari flow Pesan Laundry.',
            ]
        );
    }

    private function createPaymentForOrder(Booking $booking, string $paymentOption, float $totalPrice): Payment
    {
        $method = match ($paymentOption) {
            'transfer' => Payment::METHOD_TRANSFER,
            'qris', 'ewallet' => Payment::METHOD_EWALLET,
            default => Payment::METHOD_CASH,
        };

        $amountPaid = $paymentOption === 'cod' ? 0 : $totalPrice;

        return Payment::create([
            'booking_id' => $booking->id,
            'payment_code' => Payment::generatePaymentCode(now()->toDateString()),
            'payment_date' => now(),
            'payment_method' => $method,
            'amount_paid' => $amountPaid,
            'total_bill' => $totalPrice,
            'change_amount' => max($amountPaid - $totalPrice, 0),
            'payment_status' => Payment::statusForAmount($amountPaid, $totalPrice),
            'notes' => $paymentOption === 'cod'
                ? 'COD / Bayar di Tempat'
                : 'Pembayaran customer via '.strtoupper($paymentOption),
            'processed_by' => null,
        ]);
    }

    private function generateBookingCode(string $bookingDate): string
    {
        $year = Carbon::parse($bookingDate)->format('Y');
        $lastCode = Booking::query()
            ->where('booking_code', 'like', "LDY-{$year}-%")
            ->orderByDesc('booking_code')
            ->value('booking_code');

        $nextNumber = $lastCode ? ((int) substr($lastCode, -4)) + 1 : 1;

        return sprintf('LDY-%s-%04d', $year, $nextNumber);
    }
}
