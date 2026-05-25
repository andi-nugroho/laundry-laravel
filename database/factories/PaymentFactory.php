<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalBill = fake()->randomFloat(2, 10000, 250000);
        $amountPaid = fake()->randomElement([
            0,
            fake()->randomFloat(2, 1000, max(1000, $totalBill - 1000)),
            $totalBill,
            $totalBill + fake()->randomFloat(2, 1000, 50000),
        ]);

        return [
            'booking_id' => Booking::factory(),
            'payment_code' => 'PAY-'.now()->format('Y').'-'.fake()->unique()->numberBetween(1000, 9999),
            'payment_date' => now(),
            'payment_method' => fake()->randomElement(Payment::METHODS),
            'amount_paid' => $amountPaid,
            'total_bill' => $totalBill,
            'change_amount' => $amountPaid - $totalBill,
            'payment_status' => Payment::statusForAmount($amountPaid, $totalBill),
            'notes' => fake()->optional()->sentence(),
            'processed_by' => null,
        ];
    }
}
