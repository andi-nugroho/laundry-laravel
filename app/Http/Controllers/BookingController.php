<?php

namespace App\Http\Controllers;

use App\Support\DashboardBroadcast;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Requests\UpdateBookingStatusRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Booking::class);

        $filters = $this->bookingFilters($request);

        $query = Booking::query()
            ->with(['user', 'customer', 'service', 'payment'])
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $search = $filters['search'];

                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where('booking_code', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn ($customerQuery) => $customerQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('service', fn ($serviceQuery) => $serviceQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['status'] !== 'all', fn ($query) => $query->where('status', $filters['status']))
            ->when($filters['pickup_type'] !== 'all', fn ($query) => $query->where('pickup_type', $filters['pickup_type']))
            ->when($filters['date_from'], fn ($query) => $query->whereDate('booking_date', '>=', $filters['date_from']))
            ->when($filters['date_to'], fn ($query) => $query->whereDate('booking_date', '<=', $filters['date_to']))
            ->when($filters['payment_status'] !== 'all', function ($query) use ($filters) {
                if ($filters['payment_status'] === Payment::STATUS_UNPAID) {
                    $query->where(function ($paymentQuery) {
                        $paymentQuery
                            ->whereHas('payment', fn ($payment) => $payment->where('payment_status', Payment::STATUS_UNPAID))
                            ->orWhereDoesntHave('payment');
                    });

                    return;
                }

                $query->whereHas('payment', fn ($payment) => $payment->where('payment_status', $filters['payment_status']));
            });

        if ($request->user()->isUser()) {
            $query->where('user_id', $request->user()->id);
        }

        $this->applyBookingSort($query, $filters['sort']);

        return view('bookings.index', [
            'bookings' => $query->paginate(10)->withQueryString(),
            'filters' => $filters,
            'activeFilters' => $this->activeBookingFilters($filters),
            'statusOptions' => Booking::STATUSES,
            'paymentStatusOptions' => Payment::STATUSES,
            'pickupTypeOptions' => Booking::PICKUP_TYPES,
        ]);
    }

    public function userStatus(Request $request): View
    {
        $bookings = Booking::query()
            ->with(['customer', 'service', 'payment'])
            ->where('user_id', $request->user()->id)
            ->whereNotIn('status', [
                Booking::STATUS_SELESAI,
                Booking::STATUS_DIAMBIL,
                Booking::STATUS_DIBATALKAN,
            ])
            ->latest('booking_date')
            ->latest('id')
            ->paginate(10);

        return view('bookings.user-status', [
            'bookings' => $bookings,
        ]);
    }

    public function userHistory(Request $request): View
    {
        $bookings = Booking::query()
            ->with(['customer', 'service', 'payment'])
            ->where('user_id', $request->user()->id)
            ->whereIn('status', [
                Booking::STATUS_SELESAI,
                Booking::STATUS_DIAMBIL,
                Booking::STATUS_DIBATALKAN,
            ])
            ->latest('booking_date')
            ->latest('id')
            ->paginate(10);

        return view('bookings.user-history', [
            'bookings' => $bookings,
        ]);
    }

    public function kasirHistory(Request $request): View
    {
        $bookings = Booking::query()
            ->with(['customer', 'service', 'payment'])
            ->whereIn('status', [
                Booking::STATUS_SELESAI,
                Booking::STATUS_DIAMBIL,
                Booking::STATUS_DIBATALKAN,
            ])
            ->latest('booking_date')
            ->latest('id')
            ->paginate(15);

        return view('bookings.kasir-history', [
            'bookings' => $bookings,
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', Booking::class);

        return view('bookings.create', [
            'customers' => $this->availableCustomers($request),
            'services' => $this->availableServices(),
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $service = Service::findOrFail($data['service_id']);

        $data['booking_code'] = $this->generateBookingCode($data['booking_date']);
        $data['pickup_type'] = $data['pickup_type'] ?? Booking::PICKUP_ANTAR_SENDIRI;
        $data['status'] = Booking::STATUS_BOOKING_MASUK;
        $data = $this->syncUserFromCustomer($data);
        $data = $this->applyCalculatedFields($data, $service, $data['booking_date']);

        $booking = Booking::create($data);

        DashboardBroadcast::bookingChanged($booking);

        if ($request->user()->isUser()) {
            $paymentDate = now()->toDateTimeString();
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_code' => Payment::generatePaymentCode($paymentDate),
                'payment_date' => $paymentDate,
                'payment_method' => Payment::METHOD_EWALLET, // default dummy
                'amount_paid' => 0,
                'total_bill' => $booking->total_price,
                'change_amount' => 0,
                'payment_status' => Payment::STATUS_UNPAID,
            ]);

            DashboardBroadcast::paymentChanged($payment);

            return redirect()
                ->route('payments.pay', $payment)
                ->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
        }

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking laundry berhasil dibuat.');
    }

    public function show(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        return view('bookings.show', [
            'booking' => $booking->load(['user', 'customer', 'service', 'payment']),
        ]);
    }

    public function edit(Request $request, Booking $booking): View
    {
        Gate::authorize('update', $booking);

        return view('bookings.edit', [
            'booking' => $booking,
            'customers' => $this->availableCustomers($request),
            'services' => $this->availableServices(),
        ]);
    }

    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $data = $request->validated();

        $service = isset($data['service_id'])
            ? Service::findOrFail($data['service_id'])
            : $booking->service;

        $bookingDate = $data['booking_date'] ?? $booking->booking_date;

        if (
            array_key_exists('service_id', $data)
            || array_key_exists('booking_date', $data)
            || array_key_exists('weight', $data)
        ) {
            $data = $this->applyCalculatedFields($data, $service, $bookingDate, $booking);
        }

        if (array_key_exists('customer_id', $data)) {
            $data = $this->syncUserFromCustomer($data);
        }

        $booking->update($data);
        DashboardBroadcast::bookingChanged($booking);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking laundry berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        Gate::authorize('delete', $booking);

        DashboardBroadcast::bookingChanged($booking);
        $booking->delete();

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking laundry berhasil dihapus.');
    }

    public function updateStatus(UpdateBookingStatusRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update($request->validated());
        DashboardBroadcast::bookingChanged($booking);

        return back()->with('success', 'Status booking laundry berhasil diperbarui.');
    }

    private function generateBookingCode(string $bookingDate): string
    {
        $year = Carbon::parse($bookingDate)->format('Y');
        $lastCode = Booking::query()
            ->where('booking_code', 'like', "LDY-{$year}-%")
            ->orderByDesc('booking_code')
            ->value('booking_code');

        $nextNumber = $lastCode ? ((int) substr($lastCode, -4)) + 1 : 1;

        return sprintf('LDY-%s-%04d', $year, $nextNumber);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function applyCalculatedFields(array $data, Service $service, mixed $bookingDate, ?Booking $booking = null): array
    {
        $bookingDate = Carbon::parse($bookingDate);
        $weight = array_key_exists('weight', $data) ? $data['weight'] : $booking?->weight;

        $data['estimated_finish_date'] = $bookingDate->copy()->addDays($service->estimated_days);
        $data['total_price'] = $weight ? $weight * $service->price_per_kg : 0;

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function syncUserFromCustomer(array $data): array
    {
        if (! array_key_exists('customer_id', $data) || ! $data['customer_id']) {
            return $data;
        }

        $data['user_id'] = Customer::query()
            ->whereKey($data['customer_id'])
            ->value('user_id');

        return $data;
    }

    private function availableCustomers(Request $request)
    {
        $query = Customer::query()
            ->orderBy('name');

        if ($request->user()->isUser()) {
            $query->where('user_id', $request->user()->id);
        }

        return $query->get(['id', 'user_id', 'name', 'phone']);
    }

    private function availableServices()
    {
        return Service::query()
            ->orderBy('name')
            ->get(['id', 'name', 'price_per_kg', 'estimated_days', 'description', 'is_active']);
    }

    /**
     * @return array{search: string, status: string, payment_status: string, pickup_type: string, date_from: ?string, date_to: ?string, sort: string}
     */
    private function bookingFilters(Request $request): array
    {
        $status = (string) $request->query('status', 'all');
        $paymentStatus = (string) $request->query('payment_status', 'all');
        $pickupType = (string) $request->query('pickup_type', 'all');
        $sort = (string) $request->query('sort', 'terbaru');
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo = (string) $request->query('date_to', '');

        return [
            'search' => trim((string) $request->query('search', '')),
            'status' => in_array($status, Booking::STATUSES, true) ? $status : 'all',
            'payment_status' => in_array($paymentStatus, Payment::STATUSES, true) ? $paymentStatus : 'all',
            'pickup_type' => in_array($pickupType, Booking::PICKUP_TYPES, true) ? $pickupType : 'all',
            'date_from' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom) ? $dateFrom : null,
            'date_to' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo) ? $dateTo : null,
            'sort' => in_array($sort, ['terbaru', 'terlama', 'total_terbesar', 'total_terkecil'], true) ? $sort : 'terbaru',
        ];
    }

    private function applyBookingSort($query, string $sort): void
    {
        match ($sort) {
            'terlama' => $query->oldest('booking_date')->oldest('id'),
            'total_terbesar' => $query->orderByDesc('total_price')->latest('id'),
            'total_terkecil' => $query->orderBy('total_price')->oldest('id'),
            default => $query->latest('booking_date')->latest('id'),
        };
    }

    /**
     * @param  array{search: string, status: string, payment_status: string, pickup_type: string, date_from: ?string, date_to: ?string, sort: string}  $filters
     * @return array<string, string>
     */
    private function activeBookingFilters(array $filters): array
    {
        $active = [];

        if ($filters['search'] !== '') {
            $active['search'] = 'Search: '.$filters['search'];
        }

        if ($filters['status'] !== 'all') {
            $active['status'] = 'Status: '.str_replace('_', ' ', $filters['status']);
        }

        if ($filters['payment_status'] !== 'all') {
            $active['payment_status'] = 'Pembayaran: '.$filters['payment_status'];
        }

        if ($filters['pickup_type'] !== 'all') {
            $active['pickup_type'] = 'Pickup: '.str_replace('_', ' ', $filters['pickup_type']);
        }

        if ($filters['date_from']) {
            $active['date_from'] = 'Dari: '.$filters['date_from'];
        }

        if ($filters['date_to']) {
            $active['date_to'] = 'Sampai: '.$filters['date_to'];
        }

        if ($filters['sort'] !== 'terbaru') {
            $active['sort'] = 'Sort: '.str_replace('_', ' ', $filters['sort']);
        }

        return $active;
    }
}
