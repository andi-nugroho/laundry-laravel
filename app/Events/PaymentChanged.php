<?php

namespace App\Events;

use App\Models\Payment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentChanged implements ShouldBroadcastNow
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Payment $payment)
    {
        //
    }

    public function broadcastOn(): Channel
    {
        return new Channel('dashboard');
    }

    public function broadcastAs(): string
    {
        return 'payment.changed';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'payment_id' => $this->payment->id,
            'payment_status' => $this->payment->payment_status,
            'booking_id' => $this->payment->booking_id,
        ];
    }
}
