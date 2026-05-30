<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_booking_status(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create([
            'status' => Booking::STATUS_BOOKING_MASUK,
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('bookings.update-status', $booking), [
                'status' => Booking::STATUS_DICUCI,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_DICUCI,
        ]);
    }

    public function test_kasir_can_update_booking_status(): void
    {
        $kasir = User::factory()->kasir()->create();
        $booking = Booking::factory()->create([
            'status' => Booking::STATUS_DITERIMA,
        ]);

        $response = $this->actingAs($kasir)
            ->patch(route('bookings.update-status', $booking), [
                'status' => Booking::STATUS_DIKERINGKAN,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_DIKERINGKAN,
        ]);
    }

    public function test_user_cannot_update_booking_status(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => Booking::STATUS_BOOKING_MASUK,
        ]);

        $this->actingAs($user)
            ->patch(route('bookings.update-status', $booking), [
                'status' => Booking::STATUS_DICUCI,
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_BOOKING_MASUK,
        ]);
    }

    public function test_user_can_view_owned_booking_status(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => Booking::STATUS_DICUCI,
        ]);

        $this->actingAs($user)
            ->get(route('bookings.show', $booking))
            ->assertOk()
            ->assertSee('Dicuci')
            ->assertSee('Tracking Status');
    }

    public function test_admin_can_filter_booking_index(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create(['name' => 'Alya Filter']);
        $service = Service::factory()->create(['name' => 'Laundry Express Filter']);
        $target = Booking::factory()->create([
            'booking_code' => 'LDY-2026-FLTR',
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'booking_date' => '2026-05-10',
            'pickup_type' => Booking::PICKUP_PICKUP,
            'status' => Booking::STATUS_DICUCI,
            'total_price' => 75000,
        ]);
        Payment::factory()->create([
            'booking_id' => $target->id,
            'payment_status' => Payment::STATUS_UNPAID,
            'amount_paid' => 0,
            'total_bill' => 75000,
        ]);

        $other = Booking::factory()->create([
            'booking_code' => 'LDY-2026-OTHER',
            'booking_date' => '2026-05-20',
            'pickup_type' => Booking::PICKUP_ANTAR_SENDIRI,
            'status' => Booking::STATUS_SELESAI,
            'total_price' => 15000,
        ]);
        Payment::factory()->create([
            'booking_id' => $other->id,
            'payment_status' => Payment::STATUS_PAID,
            'amount_paid' => 15000,
            'total_bill' => 15000,
        ]);

        $this->actingAs($admin)
            ->get(route('bookings.index', [
                'search' => 'Alya',
                'status' => Booking::STATUS_DICUCI,
                'payment_status' => Payment::STATUS_UNPAID,
                'pickup_type' => Booking::PICKUP_PICKUP,
                'date_from' => '2026-05-01',
                'date_to' => '2026-05-30',
                'sort' => 'total_terbesar',
            ]))
            ->assertOk()
            ->assertSee('LDY-2026-FLTR')
            ->assertSee('Menampilkan 1 booking')
            ->assertSee('Search: Alya')
            ->assertDontSee('LDY-2026-OTHER');
    }

    public function test_unpaid_filter_includes_bookings_without_payment(): void
    {
        $kasir = User::factory()->kasir()->create();
        $withoutPayment = Booking::factory()->create([
            'booking_code' => 'LDY-2026-NOPAY',
        ]);
        $paidBooking = Booking::factory()->create([
            'booking_code' => 'LDY-2026-PAID',
        ]);
        Payment::factory()->create([
            'booking_id' => $paidBooking->id,
            'payment_status' => Payment::STATUS_PAID,
            'amount_paid' => 50000,
            'total_bill' => 50000,
        ]);

        $this->actingAs($kasir)
            ->get(route('monitoring.index', [
                'payment_status' => Payment::STATUS_UNPAID,
            ]))
            ->assertOk()
            ->assertSee('LDY-2026-NOPAY')
            ->assertDontSee('LDY-2026-PAID');
    }
}
