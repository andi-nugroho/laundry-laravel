<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function transactions(Request $request): View
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isKasir(), 403);

        $filters = $this->validatedFilters($request);

        $payments = $this->filteredPayments($filters)
            ->latest('payment_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('reports.transactions', [
            'payments' => $payments,
            'filters' => $filters,
        ]);
    }

    public function revenue(Request $request): View
    {
        abort_unless($request->user()->isAdmin(), 403);

        $filters = $this->validatedFilters($request);
        $payments = $this->filteredPayments($filters);

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
            'filters' => $filters,
            'stats' => [
                'total_revenue_paid' => (clone $paidPayments)->sum('total_bill'),
                'total_receivables' => (clone $pendingPayments)->selectRaw('COALESCE(SUM(total_bill - amount_paid), 0) as total')->value('total'),
                'paid_count' => (clone $paidPayments)->count(),
                'pending_count' => (clone $pendingPayments)->count(),
            ],
            'revenueByMethod' => $revenueByMethod,
        ]);
    }

    /**
     * @return array{start_date?: ?string, end_date?: ?string, payment_status?: ?string, payment_method?: ?string}
     */
    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'payment_status' => ['nullable', Rule::in(Payment::STATUSES)],
            'payment_method' => ['nullable', Rule::in(Payment::METHODS)],
        ]);
    }

    /**
     * @param  array{start_date?: ?string, end_date?: ?string, payment_status?: ?string, payment_method?: ?string}  $filters
     */
    private function filteredPayments(array $filters)
    {
        return Payment::query()
            ->with(['booking.customer', 'booking.service'])
            ->when($filters['start_date'] ?? null, fn ($query, $date) => $query->whereDate('payment_date', '>=', Carbon::parse($date)))
            ->when($filters['end_date'] ?? null, fn ($query, $date) => $query->whereDate('payment_date', '<=', Carbon::parse($date)))
            ->when($filters['payment_status'] ?? null, fn ($query, $status) => $query->where('payment_status', $status))
            ->when($filters['payment_method'] ?? null, fn ($query, $method) => $query->where('payment_method', $method));
    }
}
