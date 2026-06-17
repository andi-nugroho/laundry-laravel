<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_can_view_customer_list_and_detail(): void
    {
        $kasir = User::factory()->kasir()->create();
        $customer = Customer::factory()->create(['name' => 'Pelanggan Kasir']);

        $this->actingAs($kasir)
            ->get(route('customers.index'))
            ->assertOk()
            ->assertSee('Data Pelanggan')
            ->assertSee('Pelanggan Kasir');

        $this->actingAs($kasir)
            ->get(route('customers.show', $customer))
            ->assertOk()
            ->assertSee('Pelanggan Kasir');
    }

    public function test_kasir_cannot_create_update_or_delete_customers(): void
    {
        $kasir = User::factory()->kasir()->create();
        $customer = Customer::factory()->create(['name' => 'Pelanggan Lama']);

        $this->actingAs($kasir)
            ->get(route('customers.create'))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan')
            ->assertSee('Ke Dashboard');

        $this->actingAs($kasir)
            ->post(route('customers.store'), [
                'name' => 'Pelanggan Baru',
                'phone' => '08123456789',
            ])
            ->assertForbidden();

        $this->actingAs($kasir)
            ->get(route('customers.edit', $customer))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan');

        $this->actingAs($kasir)
            ->put(route('customers.update', $customer), [
                'name' => 'Pelanggan Diubah',
            ])
            ->assertForbidden();

        $this->actingAs($kasir)
            ->delete(route('customers.destroy', $customer))
            ->assertForbidden();

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Pelanggan Lama',
        ]);
    }

    public function test_kasir_customer_index_hides_edit_and_delete_actions(): void
    {
        $kasir = User::factory()->kasir()->create();
        $customer = Customer::factory()->create(['name' => 'Pelanggan Readonly']);

        $this->actingAs($kasir)
            ->get(route('customers.index'))
            ->assertOk()
            ->assertSee('Detail')
            ->assertDontSee('/customers/'.$customer->id.'/edit', false)
            ->assertDontSee('Hapus');
    }

    public function test_admin_can_crud_customers(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('customers.create'))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('customers.store'), [
                'name' => 'Pelanggan Admin',
                'phone' => '08111111111',
                'address' => 'Jl. Admin 1',
            ])
            ->assertRedirect();

        $customer = Customer::where('name', 'Pelanggan Admin')->first();
        $this->assertNotNull($customer);

        $this->actingAs($admin)
            ->get(route('customers.edit', $customer))
            ->assertOk();

        $this->actingAs($admin)
            ->put(route('customers.update', $customer), [
                'name' => 'Pelanggan Admin Updated',
                'phone' => '08222222222',
            ])
            ->assertRedirect(route('customers.show', $customer));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Pelanggan Admin Updated',
        ]);

        $this->actingAs($admin)
            ->delete(route('customers.destroy', $customer))
            ->assertRedirect(route('customers.index'));

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }
}
