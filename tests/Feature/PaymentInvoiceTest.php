<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_invoice(): void
    {
        $admin = User::factory()->admin()->create();
        $payment = Payment::factory()->create();

        $this->actingAs($admin)
            ->get(route('payments.invoice', $payment))
            ->assertOk()
            ->assertDownload("nota-{$payment->payment_code}.pdf");
    }

    public function test_kasir_can_access_invoice(): void
    {
        $kasir = User::factory()->kasir()->create();
        $payment = Payment::factory()->create();

        $this->actingAs($kasir)
            ->get(route('payments.invoice', $payment))
            ->assertOk()
            ->assertDownload("nota-{$payment->payment_code}.pdf");
    }

    public function test_user_can_access_owned_invoice(): void
    {
        $user = User::factory()->create();
        $payment = Payment::factory()->create();
        $payment->booking()->update(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('payments.invoice', $payment))
            ->assertOk()
            ->assertDownload("nota-{$payment->payment_code}.pdf");
    }

    public function test_user_cannot_access_other_invoice(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $payment = Payment::factory()->create();
        $payment->booking()->update(['user_id' => $otherUser->id]);

        $this->actingAs($user)
            ->get(route('payments.invoice', $payment))
            ->assertForbidden();
    }
}
