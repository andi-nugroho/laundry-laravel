<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Seed contoh booking laundry.
     */
    public function run(): void
    {
        $customers = Customer::query()->orderBy('id')->get();
        $services = Service::query()->orderBy('id')->get();

        if ($customers->isEmpty() || $services->isEmpty()) {
            return;
        }

        $bookingRows = [
            [
                'booking_code' => 'LDY-2026-0001',
                'customer' => $customers->get(0),
                'service' => $services->get(1) ?? $services->first(),
                'booking_date' => '2026-05-26',
                'weight' => 4.5,
                'pickup_type' => Booking::PICKUP_ANTAR_SENDIRI,
                'status' => Booking::STATUS_BOOKING_MASUK,
                'notes' => 'Pakaian harian, parfum lembut.',
            ],
            [
                'booking_code' => 'LDY-2026-0002',
                'customer' => $customers->get(1) ?? $customers->first(),
                'service' => $services->get(3) ?? $services->first(),
                'booking_date' => '2026-05-26',
                'weight' => 3.25,
                'pickup_type' => Booking::PICKUP_PICKUP,
                'status' => Booking::STATUS_DITERIMA,
                'notes' => 'Pickup sore hari.',
            ],
            [
                'booking_code' => 'LDY-2026-0003',
                'customer' => $customers->get(2) ?? $customers->first(),
                'service' => $services->get(0) ?? $services->first(),
                'booking_date' => '2026-05-25',
                'weight' => 6,
                'pickup_type' => Booking::PICKUP_ANTAR_SENDIRI,
                'status' => Booking::STATUS_DICUCI,
                'notes' => null,
            ],
            [
                'booking_code' => 'LDY-2026-0004',
                'customer' => $customers->get(3) ?? $customers->first(),
                'service' => $services->get(2) ?? $services->first(),
                'booking_date' => '2026-05-24',
                'weight' => 2.75,
                'pickup_type' => Booking::PICKUP_PICKUP,
                'status' => Booking::STATUS_SELESAI,
                'notes' => 'Pisahkan pakaian warna terang dan gelap.',
            ],
        ];

        foreach ($bookingRows as $row) {
            $service = $row['service'];
            $customer = $row['customer'];
            $bookingDate = Carbon::parse($row['booking_date']);

            Booking::updateOrCreate(
                ['booking_code' => $row['booking_code']],
                [
                    'user_id' => $customer->user_id,
                    'customer_id' => $customer->id,
                    'service_id' => $service->id,
                    'booking_date' => $bookingDate,
                    'estimated_finish_date' => $bookingDate->copy()->addDays($service->estimated_days),
                    'weight' => $row['weight'],
                    'total_price' => $row['weight'] * $service->price_per_kg,
                    'pickup_type' => $row['pickup_type'],
                    'status' => $row['status'],
                    'notes' => $row['notes'],
                ]
            );
        }
    }
}
