<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bookingDate = fake()->dateTimeBetween('-1 month', '+1 week');
        $weight = fake()->optional()->randomFloat(2, 1, 15);
        $pricePerKg = fake()->randomFloat(2, 5000, 25000);

        return [
            'booking_code' => 'LDY-'.now()->format('Y').'-'.fake()->unique()->numberBetween(1000, 9999),
            'user_id' => null,
            'customer_id' => null,
            'service_id' => Service::factory(),
            'booking_date' => $bookingDate,
            'estimated_finish_date' => fake()->optional()->dateTimeBetween($bookingDate, '+2 weeks'),
            'weight' => $weight,
            'total_price' => $weight ? $weight * $pricePerKg : 0,
            'pickup_type' => fake()->randomElement(Booking::PICKUP_TYPES),
            'status' => fake()->randomElement(Booking::STATUSES),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
