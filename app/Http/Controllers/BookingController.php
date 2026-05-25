<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Requests\UpdateBookingStatusRequest;
use App\Models\Booking;
use App\Models\Customer;
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

        $query = Booking::query()
            ->with(['user', 'customer', 'service'])
            ->latest('booking_date')
            ->latest('id');

        if ($request->user()->isUser()) {
            $query->where('user_id', $request->user()->id);
        }

        return view('bookings.index', [
            'bookings' => $query->paginate(10),
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

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking laundry berhasil dibuat.');
    }

    public function show(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        return view('bookings.show', [
            'booking' => $booking->load(['user', 'customer', 'service']),
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

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking laundry berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        Gate::authorize('delete', $booking);

        $booking->delete();

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking laundry berhasil dihapus.');
    }

    public function updateStatus(UpdateBookingStatusRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update($request->validated());

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
            ->get(['id', 'name', 'price_per_kg', 'estimated_days']);
    }
}
