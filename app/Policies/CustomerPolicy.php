<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isKasir() || $user->isUser();
    }

    public function view(User $user, Customer $customer): bool
    {
        return $this->canViewAllCustomers($user) || $customer->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isUser();
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->isAdmin() || $customer->user_id === $user->id;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->isAdmin();
    }

    private function canViewAllCustomers(User $user): bool
    {
        return $user->isAdmin() || $user->isKasir();
    }
}
