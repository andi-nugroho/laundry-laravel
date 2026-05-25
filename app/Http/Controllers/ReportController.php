<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function transactions(Request $request): View
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isKasir(), 403);

        $payments = $this->filteredPayments($request)
            ->latest('payment_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('reports.transactions', [
            'payments' => $payments,
            'filters' => $request->only(['start_date', 'end_date', 'payment_status', 'payment_method']),
        ]);
    }

    public function revenue(Request $request): View
    {
        abort_unless($request->user()->isAdmin(), 403);

        $payments = $this->filteredPayments($request);

        $paidPayments = (clone $payments)->where('payment_status', Payment::STATUS_PAID);
        $pendingPayments = (clone $payments)->whereIn('payment_status', [
            Payment::STATUS_UNPAID,
            Payment::STATUS_PARTIAL,
        ]);

        $revenueByMethod = (clone $paidPayments)
            ->selectRaw('payment_method, COALESCE(SUM(total_bill), 0) as total')
            ->groupBy('payment_method')
            ->orderBy('payment_method')
            ->pluck('total', 'payment_method');

        return view('reports.revenue', [
            'filters' => $request->only(['start_date', 'end_date', 'payment_status', 'payment_method']),
            'stats' => [
                'total_revenue_paid' => (clone $paidPayments)->sum('total_bill'),
                'total_receivables' => (clone $pendingPayments)->selectRaw('COALESCE(SUM(total_bill - amount_paid), 0) as total')->value('total'),
                'paid_count' => (clone $paidPayments)->count(),
                'pending_count' => (clone $pendingPayments)->count(),
            ],
            'revenueByMethod' => $revenueByMethod,
        ]);
    }

    private function filteredPayments(Request $request)
    {
        return Payment::query()
            ->with(['booking.customer', 'booking.service'])
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('payment_date', '>=', Carbon::parse($request->start_date)))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('payment_date', '<=', Carbon::parse($request->end_date)))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->payment_status))
            ->when($request->filled('payment_method'), fn ($query) => $query->where('payment_method', $request->payment_method));
    }
}
