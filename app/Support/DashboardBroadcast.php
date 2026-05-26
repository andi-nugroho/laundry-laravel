<?php

namespace App\Support;

use App\Events\BookingChanged;
use App\Events\PaymentChanged;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class DashboardBroadcast
{
    public static function bookingChanged(Booking $booking): void
    {
        if (! self::shouldBroadcast()) {
            return;
        }

        try {
            $payload = $booking->exists ? $booking->fresh() : $booking;

            broadcast(new BookingChanged($payload));
        } catch (\Throwable $exception) {
            Log::warning('Dashboard booking broadcast failed', [
                'booking_id' => $booking->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public static function paymentChanged(Payment $payment): void
    {
        if (! self::shouldBroadcast()) {
            return;
        }

        try {
            $payload = $payment->exists ? $payment->fresh() : $payment;

            broadcast(new PaymentChanged($payload));
        } catch (\Throwable $exception) {
            Log::warning('Dashboard payment broadcast failed', [
                'payment_id' => $payment->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private static function shouldBroadcast(): bool
    {
        return in_array(config('broadcasting.default'), ['reverb', 'pusher'], true);
    }
}
