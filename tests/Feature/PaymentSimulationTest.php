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

        $response->assertRedirect(route('user.orders.success', $booking));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_method' => Payment::METHOD_EWALLET,
            'amount_paid' => 50000,
            'payment_status' => Payment::STATUS_PAID,
            'processed_by' => $user->id,
        ]);
    }

    public function test_user_checkout_qris_redirects_to_mock_payment_page_unpaid(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['price_per_kg' => 12000, 'is_active' => true]);

        $response = $this->actingAs($user)
            ->post(route('user.orders.checkout'), [
                'service_id' => $service->id,
                'weight' => 2,
                'pickup_type' => 'antar_sendiri',
                'payment_option' => 'qris',
            ]);

        $booking = Booking::latest('id')->first();
        $payment = Payment::where('booking_id', $booking->id)->first();

        $this->assertNotNull($payment);
        $this->assertEquals(Payment::STATUS_UNPAID, $payment->payment_status);
        $this->assertEquals(0.0, (float) $payment->amount_paid);
        $this->assertStringContainsString('payment_channel=qris', $payment->notes);

        $response->assertRedirect(route('payments.pay', $payment));
        $response->assertSessionHas('success');
    }

    public function test_user_checkout_cod_goes_directly_to_success_unpaid(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['price_per_kg' => 12000, 'is_active' => true]);

        $response = $this->actingAs($user)
            ->post(route('user.orders.checkout'), [
                'service_id' => $service->id,
                'weight' => 1,
                'pickup_type' => 'pickup',
                'payment_option' => 'cod',
            ]);

        $booking = Booking::latest('id')->first();
        $payment = Payment::where('booking_id', $booking->id)->first();

        $this->assertNotNull($payment);
        $this->assertEquals(Payment::METHOD_CASH, $payment->payment_method);
        $this->assertEquals(Payment::STATUS_UNPAID, $payment->payment_status);

        $response->assertRedirect(route('user.orders.success', $booking));
        $response->assertSessionHas('success', 'Pesanan diterima. Pembayaran dilakukan di tempat dan akan dikonfirmasi oleh kasir.');
    }

    public function test_user_cannot_confirm_cod_payment(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id, 'total_price' => 50000]);
        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'payment_method' => Payment::METHOD_CASH,
            'payment_status' => Payment::STATUS_UNPAID,
            'total_bill' => 50000,
            'amount_paid' => 0,
            'notes' => 'payment_channel=cod; COD / Bayar di Tempat',
        ]);

        $this->actingAs($user)
            ->patch(route('payments.confirm', $payment))
            ->assertForbidden();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => Payment::STATUS_UNPAID,
            'amount_paid' => 0,
        ]);
    }

    public function test_kasir_can_open_payment_edit_for_cod_booking(): void
    {
        $kasir = User::factory()->kasir()->create();
        $booking = Booking::factory()->create(['total_price' => 75000]);
        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'payment_method' => Payment::METHOD_CASH,
            'payment_status' => Payment::STATUS_UNPAID,
            'total_bill' => 75000,
            'amount_paid' => 0,
            'notes' => 'payment_channel=cod; COD / Bayar di Tempat',
        ]);

        $this->actingAs($kasir)
            ->get(route('payments.edit', $payment))
            ->assertOk()
            ->assertSee('Edit Pembayaran');
    }

    public function test_kasir_can_confirm_cod_payment_as_paid(): void
    {
        $kasir = User::factory()->kasir()->create();
        $booking = Booking::factory()->create(['total_price' => 75000]);
        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'payment_method' => Payment::METHOD_CASH,
            'payment_status' => Payment::STATUS_UNPAID,
            'total_bill' => 75000,
            'amount_paid' => 0,
            'notes' => 'payment_channel=cod; COD / Bayar di Tempat',
        ]);

        $this->actingAs($kasir)
            ->put(route('payments.update', $payment), [
                'booking_id' => $booking->id,
                'payment_date' => now()->format('Y-m-d\TH:i'),
                'payment_method' => Payment::METHOD_CASH,
                'amount_paid' => 75000,
                'notes' => $payment->notes,
            ])
            ->assertRedirect(route('payments.show', $payment));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => Payment::STATUS_PAID,
            'amount_paid' => 75000,
            'processed_by' => $kasir->id,
        ]);
    }

    public function test_cod_success_page_shows_bayar_di_tempat_message(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['price_per_kg' => 12000, 'is_active' => true]);

        $this->actingAs($user)
            ->post(route('user.orders.checkout'), [
                'service_id' => $service->id,
                'weight' => 1,
                'pickup_type' => 'pickup',
                'payment_option' => 'cod',
            ]);

        $booking = Booking::latest('id')->first();

        $this->actingAs($user)
            ->get(route('user.orders.success', $booking))
            ->assertOk()
            ->assertSee('Bayar di Tempat')
            ->assertSee('Pesanan diterima. Pembayaran dilakukan di tempat dan akan dikonfirmasi oleh kasir.')
            ->assertDontSee('Saya Sudah Bayar');
    }

    public function test_mock_payment_page_shows_transfer_instructions(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id, 'total_price' => 50000]);
        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'payment_method' => Payment::METHOD_TRANSFER,
            'payment_status' => Payment::STATUS_UNPAID,
            'total_bill' => 50000,
            'amount_paid' => 0,
            'notes' => 'payment_channel=transfer; Transfer Bank BCA',
        ]);

        $this->actingAs($user)
            ->get(route('payments.pay', $payment))
            ->assertOk()
            ->assertSee('Bank BCA')
            ->assertSee('1234567890')
            ->assertSee('Saya Sudah Transfer');
    }

    public function test_mock_payment_page_shows_ewallet_instructions(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id, 'total_price' => 50000]);
        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'payment_method' => Payment::METHOD_EWALLET,
            'payment_status' => Payment::STATUS_UNPAID,
            'total_bill' => 50000,
            'amount_paid' => 0,
            'notes' => 'payment_channel=ewallet; E-Wallet mock payment',
        ]);

        $this->actingAs($user)
            ->get(route('payments.pay', $payment))
            ->assertOk()
            ->assertSee('Dana')
            ->assertSee('OVO')
            ->assertSee('GoPay')
            ->assertSee('0812-0000-2026');
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
