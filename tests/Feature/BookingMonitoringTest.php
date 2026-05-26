<?php

namespace Tests\Feature;

use App\Models\Booking;
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
}
