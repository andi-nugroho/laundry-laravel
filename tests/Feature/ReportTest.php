<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_all_reports(): void
    {
        $admin = User::factory()->admin()->create();
        $payment = Payment::factory()->create([
            'payment_code' => 'PAY-2026-9301',
            'amount_paid' => 100000,
            'total_bill' => 100000,
            'payment_status' => Payment::STATUS_PAID,
            'payment_method' => Payment::METHOD_CASH,
        ]);

        $this->actingAs($admin)
            ->get(route('reports.transactions'))
            ->assertOk()
            ->assertSee('Laporan Transaksi')
            ->assertSee($payment->payment_code);

        $this->actingAs($admin)
            ->get(route('reports.revenue'))
            ->assertOk()
            ->assertSee('Laporan Pendapatan')
            ->assertSee('Rp 100.000');
    }

    public function test_kasir_can_access_transaction_report(): void
    {
        $kasir = User::factory()->kasir()->create();
        $payment = Payment::factory()->create([
            'payment_code' => 'PAY-2026-9302',
        ]);

        $this->actingAs($kasir)
            ->get(route('reports.transactions'))
            ->assertOk()
            ->assertSee('Laporan Transaksi')
            ->assertSee($payment->payment_code);
    }

    public function test_kasir_cannot_access_revenue_report(): void
    {
        $kasir = User::factory()->kasir()->create();

        $this->actingAs($kasir)
            ->get(route('reports.revenue'))
            ->assertForbidden();
    }

    public function test_user_cannot_access_reports(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('reports.transactions'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('reports.revenue'))
            ->assertForbidden();
    }
}
