<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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
        $chartRange = $this->validatedChartRange($request);
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

        $totalRevenuePaid = (float) (clone $paidPayments)->sum('total_bill');
        $paidCount = (clone $paidPayments)->count();
        $chart = $this->buildRevenueChartData($chartRange);

        return view('reports.revenue', [
            'filters' => $filters,
            'chartRange' => $chartRange,
            'chart' => $chart,
            'stats' => [
                'total_revenue_paid' => $totalRevenuePaid,
                'total_receivables' => (clone $pendingPayments)->selectRaw('COALESCE(SUM(total_bill - amount_paid), 0) as total')->value('total'),
                'paid_count' => $paidCount,
                'pending_count' => (clone $pendingPayments)->count(),
                'average_revenue' => $paidCount > 0 ? $totalRevenuePaid / $paidCount : 0,
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

    private function validatedChartRange(Request $request): string
    {
        return $request->validate([
            'chart_range' => ['nullable', Rule::in(['7d', '1m', '1y'])],
        ])['chart_range'] ?? '7d';
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

    /**
     * @return array{labels: list<string>, values: list<float>, transactions: list<int>, granularity: string}
     */
    private function buildRevenueChartData(string $range): array
    {
        $end = Carbon::today()->endOfDay();

        if ($range === '1y') {
            $start = Carbon::today()->subMonths(11)->startOfMonth()->startOfDay();
            $periods = $this->monthlyPeriods($start, $end);
            $rows = $this->paidRevenueGrouped($start, $end, "TO_CHAR(payment_date, 'YYYY-MM')");

            return $this->formatChartData(
                $periods,
                $rows,
                fn (Carbon $date) => $date->format('Y-m'),
                fn (Carbon $date) => $date->translatedFormat('M Y'),
                'month'
            );
        }

        if ($range === '1m') {
            $start = Carbon::today()->subDays(29)->startOfDay();
            $periods = $this->dailyPeriods($start, $end);
            $rows = $this->paidRevenueGrouped($start, $end, 'DATE(payment_date)');

            return $this->formatChartData(
                $periods,
                $rows,
                fn (Carbon $date) => $date->toDateString(),
                fn (Carbon $date) => $date->format('d M'),
                'week'
            );
        }

        $start = Carbon::today()->subDays(6)->startOfDay();
        $periods = $this->dailyPeriods($start, $end);
        $rows = $this->paidRevenueGrouped($start, $end, 'DATE(payment_date)');

        return $this->formatChartData(
            $periods,
            $rows,
            fn (Carbon $date) => $date->toDateString(),
            fn (Carbon $date) => $date->format('d M'),
            'day'
        );
    }

    /**
     * @return Collection<int, Carbon>
     */
    private function dailyPeriods(Carbon $start, Carbon $end): Collection
    {
        $periods = collect();
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $periods->push($cursor->copy());
            $cursor->addDay();
        }

        return $periods;
    }

    /**
     * @return Collection<int, Carbon>
     */
    private function monthlyPeriods(Carbon $start, Carbon $end): Collection
    {
        $periods = collect();
        $cursor = $start->copy()->startOfMonth();

        while ($cursor->lte($end)) {
            $periods->push($cursor->copy());
            $cursor->addMonth();
        }

        return $periods;
    }

    /**
     * @return Collection<string, object{period: string, total: string|float, transactions: int}>
     */
    private function paidRevenueGrouped(Carbon $start, Carbon $end, string $periodExpression): Collection
    {
        return Payment::query()
            ->where('payment_status', Payment::STATUS_PAID)
            ->whereBetween('payment_date', [$start, $end])
            ->selectRaw("{$periodExpression} as period, COALESCE(SUM(total_bill), 0) as total, COUNT(*) as transactions")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy(fn ($row) => (string) $row->period);
    }

    /**
     * @param  Collection<int, Carbon>  $periods
     * @param  Collection<string, object{period: string, total: string|float, transactions: int}>  $rows
     * @return array{labels: list<string>, values: list<float>, transactions: list<int>, granularity: string}
     */
    private function formatChartData(
        Collection $periods,
        Collection $rows,
        callable $keyResolver,
        callable $labelResolver,
        string $granularity
    ): array {
        $labels = [];
        $values = [];
        $transactions = [];

        foreach ($periods as $period) {
            $key = $keyResolver($period);
            $row = $rows->get($key);

            $labels[] = $labelResolver($period);
            $values[] = (float) ($row->total ?? 0);
            $transactions[] = (int) ($row->transactions ?? 0);
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'transactions' => $transactions,
            'granularity' => $granularity,
        ];
    }
}
