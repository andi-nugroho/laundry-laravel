<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Layanan Test',
            'description' => 'Deskripsi layanan test',
            'price_per_kg' => 10000,
            'estimated_days' => 2,
            'is_active' => true,
        ], $overrides);
    }

    public function test_guest_cannot_access_services(): void
    {
        $this->get(route('services.index'))->assertRedirect('/login');
        $this->get(route('services.create'))->assertRedirect('/login');
    }

    public function test_kasir_cannot_access_services(): void
    {
        $kasir = User::factory()->kasir()->create();

        $this->actingAs($kasir)
            ->get(route('services.index'))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan')
            ->assertSee('Ke Dashboard');

        $this->actingAs($kasir)
            ->get(route('services.create'))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan');

        $service = Service::factory()->create();

        $this->actingAs($kasir)
            ->post(route('services.store'), $this->validPayload())
            ->assertForbidden();

        $this->actingAs($kasir)
            ->get(route('services.edit', $service))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan');

        $this->actingAs($kasir)
            ->put(route('services.update', $service), $this->validPayload())
            ->assertForbidden();

        $this->actingAs($kasir)
            ->delete(route('services.destroy', $service))
            ->assertForbidden();
    }

    public function test_user_cannot_access_services(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('services.index'))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan')
            ->assertSee('Ke Dashboard');

        $this->actingAs($user)
            ->get(route('services.create'))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan');

        $service = Service::factory()->create();

        $this->actingAs($user)
            ->post(route('services.store'), $this->validPayload())
            ->assertForbidden();

        $this->actingAs($user)
            ->delete(route('services.destroy', $service))
            ->assertForbidden();
    }

    public function test_admin_can_view_services_index(): void
    {
        Service::factory()->count(2)->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('services.index'))
            ->assertOk()
            ->assertSee('Layanan Laundry');
    }

    public function test_admin_can_create_service(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->post(route('services.store'), $this->validPayload([
                'name' => 'Cuci Premium',
            ]));

        $response->assertRedirect(route('services.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('services', [
            'name' => 'Cuci Premium',
            'price_per_kg' => 10000,
            'estimated_days' => 2,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_service(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['name' => 'Lama']);

        $response = $this->actingAs($admin)
            ->put(route('services.update', $service), $this->validPayload([
                'name' => 'Baru',
                'price_per_kg' => 15000,
                'estimated_days' => 3,
            ]));

        $response->assertRedirect(route('services.index'));

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Baru',
            'price_per_kg' => 15000,
            'estimated_days' => 3,
        ]);
    }

    public function test_admin_can_delete_service(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();

        $response = $this->actingAs($admin)
            ->delete(route('services.destroy', $service));

        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_store_validation_requires_name_and_price(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->post(route('services.store'), []);

        $response->assertSessionHasErrors(['name', 'price_per_kg', 'estimated_days']);
    }

    public function test_store_validation_rejects_duplicate_name(): void
    {
        $admin = User::factory()->admin()->create();
        Service::factory()->create(['name' => 'Cuci Kering']);

        $response = $this->actingAs($admin)
            ->post(route('services.store'), $this->validPayload(['name' => 'Cuci Kering']));

        $response->assertSessionHasErrors('name');
    }

    public function test_update_validation_rejects_duplicate_name_from_other_service(): void
    {
        $admin = User::factory()->admin()->create();
        Service::factory()->create(['name' => 'Cuci Kering']);
        $service = Service::factory()->create(['name' => 'Cuci Setrika']);

        $response = $this->actingAs($admin)
            ->put(route('services.update', $service), $this->validPayload(['name' => 'Cuci Kering']));

        $response->assertSessionHasErrors('name');
    }

    public function test_service_seeder_creates_example_services(): void
    {
        $this->seed(\Database\Seeders\ServiceSeeder::class);

        $this->assertDatabaseCount('services', 6);
        $this->assertDatabaseHas('services', ['name' => 'Cuci Kering']);
        $this->assertDatabaseHas('services', ['name' => 'Laundry Bedcover']);
    }
}
