<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Service;
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
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan')
            ->assertSee('Ke Dashboard');
    }

    public function test_kasir_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->kasir()->create();

        $this->actingAs($user)
            ->get(route('dashboard.admin'))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan')
            ->assertSee('Ke Dashboard');
    }

    public function test_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.admin'))
            ->assertForbidden()
            ->assertSee('Akses Tidak Diizinkan')
            ->assertSee('Ke Dashboard');
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

    public function test_admin_dashboard_renders_real_statistics(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['is_active' => true]);
        Service::factory()->create(['is_active' => false]);
        Customer::factory()->count(2)->create();

        $bookingMasuk = Booking::factory()->create([
            'service_id' => $service->id,
            'booking_code' => 'LDY-2026-9001',
            'booking_date' => today(),
            'status' => Booking::STATUS_BOOKING_MASUK,
            'total_price' => 100000,
        ]);
        $bookingProses = Booking::factory()->create([
            'service_id' => $service->id,
            'booking_code' => 'LDY-2026-9002',
            'booking_date' => today(),
            'status' => Booking::STATUS_DICUCI,
            'total_price' => 200000,
        ]);
        $bookingSelesai = Booking::factory()->create([
            'service_id' => $service->id,
            'booking_code' => 'LDY-2026-9003',
            'booking_date' => today()->subDay(),
            'status' => Booking::STATUS_SELESAI,
            'total_price' => 300000,
        ]);

        Payment::factory()->create([
            'booking_id' => $bookingMasuk->id,
            'payment_code' => 'PAY-2026-9001',
            'amount_paid' => 100000,
            'total_bill' => 100000,
            'change_amount' => 0,
            'payment_status' => Payment::STATUS_PAID,
        ]);
        Payment::factory()->create([
            'booking_id' => $bookingProses->id,
            'payment_code' => 'PAY-2026-9002',
            'amount_paid' => 50000,
            'total_bill' => 200000,
            'change_amount' => 0,
            'payment_status' => Payment::STATUS_PARTIAL,
        ]);
        Payment::factory()->create([
            'booking_id' => $bookingSelesai->id,
            'payment_code' => 'PAY-2026-9003',
            'amount_paid' => 0,
            'total_bill' => 300000,
            'change_amount' => 0,
            'payment_status' => Payment::STATUS_UNPAID,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard.admin'))
            ->assertOk()
            ->assertSee('Total Customers')
            ->assertSee('Grafik Pendapatan')
            ->assertSee('adminRevenueChart')
            ->assertSee('2')
            ->assertSee('Services Aktif')
            ->assertSee('1')
            ->assertSee('LDY-2026-9001')
            ->assertSee('PAY-2026-9001')
            ->assertSee('Rp 100.000')
            ->assertSee('Rp 450.000');
    }

    public function test_kasir_dashboard_renders_real_statistics(): void
    {
        $kasir = User::factory()->kasir()->create();
        $service = Service::factory()->create();
        $booking = Booking::factory()->create([
            'service_id' => $service->id,
            'booking_code' => 'LDY-2026-9101',
            'booking_date' => today(),
            'status' => Booking::STATUS_DITERIMA,
            'total_price' => 150000,
        ]);

        Payment::factory()->create([
            'booking_id' => $booking->id,
            'payment_code' => 'PAY-2026-9101',
            'payment_date' => now(),
            'amount_paid' => 50000,
            'total_bill' => 150000,
            'change_amount' => 0,
            'payment_status' => Payment::STATUS_PARTIAL,
        ]);

        $this->actingAs($kasir)
            ->get(route('dashboard.kasir'))
            ->assertOk()
            ->assertSee('Booking Masuk Hari Ini')
            ->assertSee('Laundry Sedang Diproses')
            ->assertSee('Payment Belum Lunas')
            ->assertSee('Rp 50.000')
            ->assertSee('LDY-2026-9101')
            ->assertSee('PAY-2026-9101');
    }

    public function test_user_dashboard_renders_owned_statistics(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $service = Service::factory()->create();
        $ownedBooking = Booking::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'booking_code' => 'LDY-2026-9201',
            'status' => Booking::STATUS_DICUCI,
            'total_price' => 125000,
        ]);
        Booking::factory()->create([
            'user_id' => $otherUser->id,
            'service_id' => $service->id,
            'booking_code' => 'LDY-2026-9202',
            'status' => Booking::STATUS_DICUCI,
            'total_price' => 225000,
        ]);

        Payment::factory()->create([
            'booking_id' => $ownedBooking->id,
            'payment_code' => 'PAY-2026-9201',
            'amount_paid' => 125000,
            'total_bill' => 125000,
            'change_amount' => 0,
            'payment_status' => Payment::STATUS_PAID,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard.user'))
            ->assertOk()
            ->assertSee('Total Booking Saya')
            ->assertSee('Total Pengeluaran')
            ->assertSee('Grafik Pengeluaran Saya')
            ->assertSee('userSpendingChart')
            ->assertSee('Rp 125.000')
            ->assertSee('LDY-2026-9201')
            ->assertDontSee('LDY-2026-9202');

        foreach (['7d', '1m', '1y'] as $range) {
            $this->actingAs($user)
                ->get(route('dashboard.user', ['chart_range' => $range]))
                ->assertOk()
                ->assertSee('Grafik Pengeluaran Saya');
        }
    }
}
