<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Seed contoh transaksi pembayaran.
     */
    public function run(): void
    {
        $bookings = Booking::query()
            ->orderBy('booking_code')
            ->get();

        if ($bookings->isEmpty()) {
            return;
        }

        $admin = User::where('email', 'admin@laundry.test')->first();
        $kasir = User::where('email', 'kasir@laundry.test')->first();

        $paymentRows = [
            [
                'payment_code' => 'PAY-2026-0001',
                'booking' => $bookings->get(0),
                'payment_date' => '2026-05-26 09:00:00',
                'payment_method' => Payment::METHOD_CASH,
                'amount_paid' => 0,
                'processed_by' => null,
                'notes' => 'Belum ada pembayaran.',
            ],
            [
                'payment_code' => 'PAY-2026-0002',
                'booking' => $bookings->get(1) ?? $bookings->first(),
                'payment_date' => '2026-05-26 10:30:00',
                'payment_method' => Payment::METHOD_TRANSFER,
                'amount_paid' => 25000,
                'processed_by' => $kasir?->id,
                'notes' => 'Pembayaran DP.',
            ],
            [
                'payment_code' => 'PAY-2026-0003',
                'booking' => $bookings->get(2) ?? $bookings->first(),
                'payment_date' => '2026-05-26 13:15:00',
                'payment_method' => Payment::METHOD_EWALLET,
                'amount_paid' => null,
                'processed_by' => $kasir?->id,
                'notes' => 'Pembayaran lunas via e-wallet.',
            ],
            [
                'payment_code' => 'PAY-2026-0004',
                'booking' => $bookings->get(3) ?? $bookings->first(),
                'payment_date' => '2026-05-26 16:00:00',
                'payment_method' => Payment::METHOD_CASH,
                'amount_paid' => null,
                'processed_by' => $admin?->id,
                'notes' => 'Pembayaran tunai dengan kembalian.',
            ],
        ];

        foreach ($paymentRows as $index => $row) {
            $booking = $row['booking'];
            $totalBill = (float) $booking->total_price;
            $amountPaid = match ($index) {
                2 => $totalBill,
                3 => $totalBill + 10000,
                default => (float) $row['amount_paid'],
            };

            Payment::updateOrCreate(
                ['payment_code' => $row['payment_code']],
                [
                    'booking_id' => $booking->id,
                    'payment_date' => $row['payment_date'],
                    'payment_method' => $row['payment_method'],
                    'amount_paid' => $amountPaid,
                    'total_bill' => $totalBill,
                    'change_amount' => $amountPaid - $totalBill,
                    'payment_status' => Payment::statusForAmount($amountPaid, $totalBill),
                    'notes' => $row['notes'],
                    'processed_by' => $row['processed_by'],
                ]
            );
        }
    }
}
