<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isKasir() || $user->isUser();
    }

    public function view(User $user, Payment $payment): bool
    {
        return $this->canManagePayments($user)
            || $payment->booking?->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $this->canManagePayments($user);
    }

    public function update(User $user, Payment $payment): bool
    {
        return $this->canManagePayments($user);
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->isAdmin();
    }

    private function canManagePayments(User $user): bool
    {
        return $user->isAdmin() || $user->isKasir();
    }
}
