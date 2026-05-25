<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_role_dashboards(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/kasir/dashboard')->assertRedirect('/login');
        $this->get('/user/dashboard')->assertRedirect('/login');
    }

    public function test_dashboard_redirects_admin_to_admin_dashboard(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('dashboard.admin', absolute: false));
    }

    public function test_dashboard_redirects_kasir_to_kasir_dashboard(): void
    {
        $user = User::factory()->kasir()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('dashboard.kasir', absolute: false));
    }

    public function test_dashboard_redirects_user_to_user_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('dashboard.user', absolute: false));
    }

    public function test_admin_can_view_admin_dashboard(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->get(route('dashboard.admin'))
            ->assertOk()
            ->assertSee('Dashboard Statistik');
    }

    public function test_kasir_can_view_kasir_dashboard(): void
    {
        $user = User::factory()->kasir()->create();

        $this->actingAs($user)
            ->get(route('dashboard.kasir'))
            ->assertOk()
            ->assertSee('Dashboard Operasional');
    }

    public function test_user_can_view_user_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.user'))
            ->assertOk()
            ->assertSee('Dashboard Pelanggan');
    }

    public function test_admin_cannot_access_kasir_dashboard(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->get(route('dashboard.kasir'))
            ->assertForbidden();
    }

    public function test_kasir_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->kasir()->create();

        $this->actingAs($user)
            ->get(route('dashboard.admin'))
            ->assertForbidden();
    }

    public function test_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.admin'))
            ->assertForbidden();
    }

    public function test_login_redirects_admin_to_role_dashboard_via_dashboard_route(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('dashboard.admin', absolute: false));
    }
}
