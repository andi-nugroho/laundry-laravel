<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSimulationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_redirected_to_payment_after_booking(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['price_per_kg' => 10000]);

        $response = $this->actingAs($user)
            ->post(route('bookings.store'), [
                'service_id' => $service->id,
                'weight' => 2,
                'booking_date' => now()->format('Y-m-d'),
                'pickup_type' => 'antar_sendiri',
                'customer_name' => 'John Doe',
                'customer_phone' => '08123456789',
            ]);

        $booking = Booking::latest('id')->first();
        $payment = Payment::where('booking_id', $booking->id)->first();

        $this->assertNotNull($payment);
        $this->assertEquals(Payment::STATUS_UNPAID, $payment->payment_status);

        $response->assertRedirect(route('payments.pay', $payment));
        $response->assertSessionHas('success');
    }

    public function test_user_can_confirm_own_payment(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id, 'total_price' => 50000]);
        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'payment_status' => Payment::STATUS_UNPAID,
            'total_bill' => 50000,
            'amount_paid' => 0,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('payments.confirm', $payment), [
                'payment_method' => 'qris',
            ]);

        $response->assertRedirect(route('payments.show', $payment));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_method' => Payment::METHOD_EWALLET,
            'amount_paid' => 50000,
            'payment_status' => Payment::STATUS_PAID,
            'processed_by' => $user->id,
        ]);
    }

    public function test_user_cannot_confirm_other_payment(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user1->id, 'total_price' => 50000]);
        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'payment_status' => Payment::STATUS_UNPAID,
            'total_bill' => 50000,
            'amount_paid' => 0,
        ]);

        $response = $this->actingAs($user2)
            ->patch(route('payments.confirm', $payment), [
                'payment_method' => 'qris',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => Payment::STATUS_UNPAID,
            'amount_paid' => 0,
        ]);
    }
}
