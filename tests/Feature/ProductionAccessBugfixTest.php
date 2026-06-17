<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionAccessBugfixTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_cannot_edit_user(): void
    {
        $kasir = User::factory()->kasir()->create();
        $target = User::factory()->create();

        $this->actingAs($kasir)
            ->get(route('users.edit', $target))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan')
            ->assertSee('Ke Dashboard');

        $this->actingAs($kasir)
            ->put(route('users.update', $target), [
                'name' => 'Changed by Kasir',
                'email' => 'changed-by-kasir@example.test',
                'role' => User::ROLE_ADMIN,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'id' => $target->id,
            'email' => 'changed-by-kasir@example.test',
        ]);
    }

    public function test_user_access_admin_route_gets_custom_403_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.admin'))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan')
            ->assertSee('Anda tidak memiliki izin untuk membuka halaman ini.')
            ->assertSee('Ke Dashboard');
    }

    public function test_admin_can_edit_user(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('users.edit', $target))
            ->assertOk()
            ->assertSee('Edit User');

        $response = $this->actingAs($admin)
            ->put(route('users.update', $target), [
                'name' => 'Kasir Baru',
                'email' => 'kasir-baru@example.test',
                'role' => User::ROLE_KASIR,
            ]);

        $response->assertRedirect(route('users.edit', $target));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'name' => 'Kasir Baru',
            'email' => 'kasir-baru@example.test',
            'role' => User::ROLE_KASIR,
        ]);
    }

    public function test_admin_can_edit_booking(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();
        $service = Service::factory()->create([
            'price_per_kg' => 12000,
            'estimated_days' => 3,
        ]);
        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'booking_date' => '2026-06-01',
            'weight' => 2,
            'total_price' => 24000,
        ]);

        $this->actingAs($admin)
            ->get(route('bookings.edit', $booking))
            ->assertOk();

        $response = $this->actingAs($admin)
            ->put(route('bookings.update', $booking), [
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'booking_date' => '2026-06-02',
                'weight' => 3,
                'pickup_type' => Booking::PICKUP_PICKUP,
                'notes' => 'Update dari admin',
            ]);

        $response->assertRedirect(route('bookings.show', $booking));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'booking_date' => '2026-06-02',
            'weight' => 3,
            'total_price' => 36000,
            'pickup_type' => Booking::PICKUP_PICKUP,
        ]);
    }

    public function test_kasir_can_input_payment_for_unpaid_booking(): void
    {
        $kasir = User::factory()->kasir()->create();
        $booking = Booking::factory()->create([
            'total_price' => 50000,
        ]);

        $response = $this->actingAs($kasir)
            ->post(route('payments.store'), [
                'booking_id' => $booking->id,
                'payment_date' => '2026-06-16 10:00:00',
                'payment_method' => Payment::METHOD_CASH,
                'amount_paid' => 50000,
                'notes' => 'Dibayar kasir',
            ]);

        $payment = Payment::where('booking_id', $booking->id)->firstOrFail();

        $response->assertRedirect(route('payments.show', $payment));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'booking_id' => $booking->id,
            'amount_paid' => 50000,
            'total_bill' => 50000,
            'change_amount' => 0,
            'payment_status' => Payment::STATUS_PAID,
            'processed_by' => $kasir->id,
        ]);
    }

    public function test_admin_can_input_payment_for_existing_unpaid_payment(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->create([
            'total_price' => 75000,
        ]);
        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'payment_method' => Payment::METHOD_CASH,
            'payment_status' => Payment::STATUS_UNPAID,
            'amount_paid' => 0,
            'total_bill' => 75000,
            'change_amount' => 0,
            'processed_by' => null,
        ]);

        $this->actingAs($admin)
            ->get(route('payments.edit', $payment))
            ->assertOk();

        $response = $this->actingAs($admin)
            ->put(route('payments.update', $payment), [
                'booking_id' => $booking->id,
                'payment_date' => '2026-06-16 11:00:00',
                'payment_method' => Payment::METHOD_TRANSFER,
                'amount_paid' => 75000,
                'notes' => 'Dibayar admin',
            ]);

        $response->assertRedirect(route('payments.show', $payment));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'amount_paid' => 75000,
            'total_bill' => 75000,
            'change_amount' => 0,
            'payment_status' => Payment::STATUS_PAID,
            'processed_by' => $admin->id,
        ]);
    }

    public function test_user_cannot_access_other_users_booking_or_payment(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $owner->id,
        ]);
        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
        ]);

        $this->actingAs($otherUser)
            ->get(route('bookings.show', $booking))
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->get(route('payments.show', $payment))
            ->assertForbidden();
    }
}
