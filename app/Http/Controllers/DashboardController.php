<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Redirect ke dashboard sesuai role pengguna yang login.
     */
    public function redirect(): RedirectResponse
    {
        return redirect()->route(Auth::user()->dashboardRouteName());
    }

    /**
     * Dashboard statistik admin/owner.
     */
    public function admin(): View
    {
        return view('dashboard.admin', [
            'stats' => $this->adminStats(),
            'recentBookings' => Booking::query()
                ->with(['customer', 'service'])
                ->latest('booking_date')
                ->latest('id')
                ->limit(5)
                ->get(),
            'recentPayments' => Payment::query()
                ->with(['booking.customer', 'processedBy'])
                ->latest('payment_date')
                ->latest('id')
                ->limit(5)
                ->get(),
        ]);
    }

    /**
     * Dashboard operasional kasir.
     */
    public function kasir(): View
    {
        return view('dashboard.kasir', [
            'stats' => $this->kasirStats(),
            'processBookings' => Booking::query()
                ->with(['customer', 'service'])
                ->whereIn('status', array_merge([Booking::STATUS_BOOKING_MASUK], $this->processingStatuses()))
                ->latest('booking_date')
                ->latest('id')
                ->limit(5)
                ->get(),
            'pendingPayments' => Payment::query()
                ->with(['booking.customer'])
                ->whereIn('payment_status', [
                    Payment::STATUS_UNPAID,
                    Payment::STATUS_PARTIAL,
                ])
                ->latest('payment_date')
                ->latest('id')
                ->limit(5)
                ->get(),
        ]);
    }

    /**
     * Dashboard pelanggan.
     */
    public function user(): View
    {
        $user = Auth::user();

        return view('dashboard.user', [
            'stats' => $this->userStats($user->id),
            'recentBookings' => Booking::query()
                ->with(['service', 'payment'])
                ->where('user_id', $user->id)
                ->latest('booking_date')
                ->latest('id')
                ->limit(5)
                ->get(),
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();

        $stats = match ($user->role) {
            \App\Models\User::ROLE_ADMIN => $this->adminStats(),
            \App\Models\User::ROLE_KASIR => $this->kasirStats(),
            default => $this->userStats($user->id),
        };

        return response()
            ->json([
                'role' => $user->role,
                'updated_at' => now()->format('H:i:s'),
                'stats' => collect($stats)
                    ->map(fn ($value, $key) => [
                        'raw' => $value,
                        'formatted' => $this->formatStatValue((string) $key, $value),
                    ])
                    ->all(),
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    /**
     * @return array<string, int|float|string|null>
     */
    private function adminStats(): array
    {
        return [
            'total_customers' => Customer::query()->count(),
            'total_services_active' => Service::query()->where('is_active', true)->count(),
            'total_bookings' => Booking::query()->count(),
            'total_bookings_today' => Booking::query()->whereDate('booking_date', today())->count(),
            'booking_masuk' => Booking::query()->where('status', Booking::STATUS_BOOKING_MASUK)->count(),
            'laundry_processing' => Booking::query()->whereIn('status', $this->processingStatuses())->count(),
            'laundry_done' => Booking::query()->where('status', Booking::STATUS_SELESAI)->count(),
            'total_payments' => Payment::query()->count(),
            'total_revenue_paid' => (float) Payment::query()->where('payment_status', Payment::STATUS_PAID)->sum('total_bill'),
            'total_receivables' => (float) Payment::query()->whereIn('payment_status', [
                Payment::STATUS_UNPAID,
                Payment::STATUS_PARTIAL,
            ])->selectRaw('COALESCE(SUM(total_bill - amount_paid), 0) as total')->value('total'),
        ];
    }

    /**
     * @return array<string, int|float|string|null>
     */
    private function kasirStats(): array
    {
        return [
            'booking_today' => Booking::query()->whereDate('booking_date', today())->count(),
            'laundry_processing' => Booking::query()->whereIn('status', $this->processingStatuses())->count(),
            'payment_pending' => Payment::query()->whereIn('payment_status', [
                Payment::STATUS_UNPAID,
                Payment::STATUS_PARTIAL,
            ])->count(),
            'payment_today_total' => (float) Payment::query()->whereDate('payment_date', today())->sum('amount_paid'),
        ];
    }

    /**
     * @return array<string, int|float|string|null>
     */
    private function userStats(int $userId): array
    {
        return [
            'total_bookings' => Booking::query()->where('user_id', $userId)->count(),
            'active_bookings' => Booking::query()->where('user_id', $userId)
                ->whereIn('status', $this->activeUserStatuses())
                ->count(),
            'done_bookings' => Booking::query()->where('user_id', $userId)
                ->where('status', Booking::STATUS_SELESAI)
                ->count(),
            'paid_payments_total' => (float) Payment::query()->whereHas('booking', fn ($query) => $query->where('user_id', $userId))
                ->where('payment_status', Payment::STATUS_PAID)
                ->sum('total_bill'),
        ];
    }

    /**
     * @return list<string>
     */
    private function processingStatuses(): array
    {
        return [
            Booking::STATUS_DITERIMA,
            Booking::STATUS_DICUCI,
            Booking::STATUS_DIKERINGKAN,
            Booking::STATUS_DISETRIKA,
        ];
    }

    /**
     * @return list<string>
     */
    private function activeUserStatuses(): array
    {
        return array_merge([Booking::STATUS_BOOKING_MASUK], $this->processingStatuses());
    }

    private function formatStatValue(string $key, mixed $value): string
    {
        $currencyKeys = [
            'total_revenue_paid',
            'total_receivables',
            'payment_today_total',
            'paid_payments_total',
        ];

        if (in_array($key, $currencyKeys, true)) {
            return 'Rp '.number_format((float) $value, 0, ',', '.');
        }

        return number_format((float) $value, 0, ',', '.');
    }
}
