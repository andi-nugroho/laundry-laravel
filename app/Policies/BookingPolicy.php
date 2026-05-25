<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isKasir() || $user->isUser();
    }

    public function view(User $user, Booking $booking): bool
    {
        return $this->canManageAllBookings($user) || $booking->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isKasir() || $user->isUser();
    }

    public function update(User $user, Booking $booking): bool
    {
        if ($this->canManageAllBookings($user)) {
            return true;
        }

        return $booking->user_id === $user->id
            && $booking->status === Booking::STATUS_BOOKING_MASUK;
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $this->canManageAllBookings($user);
    }

    public function updateStatus(User $user, Booking $booking): bool
    {
        return $this->canManageAllBookings($user);
    }

    private function canManageAllBookings(User $user): bool
    {
        return $user->isAdmin() || $user->isKasir();
    }
}
