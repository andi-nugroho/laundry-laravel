<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
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
        $processingStatuses = [
            Booking::STATUS_DITERIMA,
            Booking::STATUS_DICUCI,
            Booking::STATUS_DIKERINGKAN,
            Booking::STATUS_DISETRIKA,
        ];

        return view('dashboard.admin', [
            'stats' => [
                'total_customers' => Customer::count(),
                'total_services_active' => Service::where('is_active', true)->count(),
                'total_bookings' => Booking::count(),
                'total_bookings_today' => Booking::whereDate('booking_date', today())->count(),
                'booking_masuk' => Booking::where('status', Booking::STATUS_BOOKING_MASUK)->count(),
                'laundry_processing' => Booking::whereIn('status', $processingStatuses)->count(),
                'laundry_done' => Booking::where('status', Booking::STATUS_SELESAI)->count(),
                'total_payments' => Payment::count(),
                'total_revenue_paid' => Payment::where('payment_status', Payment::STATUS_PAID)->sum('total_bill'),
                'total_receivables' => Payment::whereIn('payment_status', [
                    Payment::STATUS_UNPAID,
                    Payment::STATUS_PARTIAL,
                ])->selectRaw('COALESCE(SUM(total_bill - amount_paid), 0) as total')->value('total'),
            ],
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
        $processingStatuses = [
            Booking::STATUS_DITERIMA,
            Booking::STATUS_DICUCI,
            Booking::STATUS_DIKERINGKAN,
            Booking::STATUS_DISETRIKA,
        ];

        return view('dashboard.kasir', [
            'stats' => [
                'booking_today' => Booking::whereDate('booking_date', today())->count(),
                'laundry_processing' => Booking::whereIn('status', $processingStatuses)->count(),
                'payment_pending' => Payment::whereIn('payment_status', [
                    Payment::STATUS_UNPAID,
                    Payment::STATUS_PARTIAL,
                ])->count(),
                'payment_today_total' => Payment::whereDate('payment_date', today())->sum('amount_paid'),
            ],
            'processBookings' => Booking::query()
                ->with(['customer', 'service'])
                ->whereIn('status', array_merge([Booking::STATUS_BOOKING_MASUK], $processingStatuses))
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
        $activeStatuses = [
            Booking::STATUS_BOOKING_MASUK,
            Booking::STATUS_DITERIMA,
            Booking::STATUS_DICUCI,
            Booking::STATUS_DIKERINGKAN,
            Booking::STATUS_DISETRIKA,
        ];

        return view('dashboard.user', [
            'stats' => [
                'total_bookings' => Booking::where('user_id', $user->id)->count(),
                'active_bookings' => Booking::where('user_id', $user->id)
                    ->whereIn('status', $activeStatuses)
                    ->count(),
                'done_bookings' => Booking::where('user_id', $user->id)
                    ->where('status', Booking::STATUS_SELESAI)
                    ->count(),
                'paid_payments_total' => Payment::whereHas('booking', fn ($query) => $query->where('user_id', $user->id))
                    ->where('payment_status', Payment::STATUS_PAID)
                    ->sum('total_bill'),
            ],
            'recentBookings' => Booking::query()
                ->with(['service', 'payment'])
                ->where('user_id', $user->id)
                ->latest('booking_date')
                ->latest('id')
                ->limit(5)
                ->get(),
        ]);
    }
}
