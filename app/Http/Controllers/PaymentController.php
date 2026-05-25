<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Payment::class);

        $query = Payment::query()
            ->with(['booking.customer', 'booking.service', 'processedBy'])
            ->latest('payment_date')
            ->latest('id');

        if ($request->user()->isUser()) {
            $query->whereHas('booking', fn ($bookingQuery) => $bookingQuery->where('user_id', $request->user()->id));
        }

        return view('payments.index', [
            'payments' => $query->paginate(10),
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', Payment::class);

        return view('payments.create', [
            'bookings' => $this->availableBookings(),
            'selectedBookingId' => $request->integer('booking_id') ?: null,
        ]);
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $booking = Booking::findOrFail($data['booking_id']);

        $data['payment_code'] = $this->generatePaymentCode($data['payment_date']);
        $data = $this->applyCalculatedFields($data, $booking);
        $data['processed_by'] = $request->user()->id;

        $payment = Payment::create($data);

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dibuat.');
    }

    public function show(Payment $payment): View
    {
        Gate::authorize('view', $payment);

        return view('payments.show', [
            'payment' => $payment->load(['booking.customer', 'booking.service', 'processedBy']),
        ]);
    }

    public function invoice(Payment $payment): View
    {
        Gate::authorize('view', $payment);

        return view('payments.invoice', [
            'payment' => $payment->load(['booking.customer', 'booking.service', 'processedBy']),
        ]);
    }

    public function edit(Payment $payment): View
    {
        Gate::authorize('update', $payment);

        return view('payments.edit', [
            'payment' => $payment,
            'bookings' => $this->availableBookings($payment),
        ]);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): RedirectResponse
    {
        $data = $request->validated();

        $booking = isset($data['booking_id'])
            ? Booking::findOrFail($data['booking_id'])
            : $payment->booking;

        $data = $this->applyCalculatedFields($data, $booking, $payment);
        $data['processed_by'] = $request->user()->id;

        $payment->update($data);

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        Gate::authorize('delete', $payment);

        $payment->delete();

        return redirect()
            ->route('payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    private function generatePaymentCode(string $paymentDate): string
    {
        $year = Carbon::parse($paymentDate)->format('Y');
        $lastCode = Payment::query()
            ->where('payment_code', 'like', "PAY-{$year}-%")
            ->orderByDesc('payment_code')
            ->value('payment_code');

        $nextNumber = $lastCode ? ((int) substr($lastCode, -4)) + 1 : 1;

        return sprintf('PAY-%s-%04d', $year, $nextNumber);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function applyCalculatedFields(array $data, Booking $booking, ?Payment $payment = null): array
    {
        $totalBill = (float) $booking->total_price;
        $amountPaid = array_key_exists('amount_paid', $data)
            ? (float) $data['amount_paid']
            : (float) $payment?->amount_paid;

        $data['total_bill'] = $totalBill;
        $data['change_amount'] = max($amountPaid - $totalBill, 0);
        $data['payment_status'] = Payment::statusForAmount($amountPaid, $totalBill);

        return $data;
    }

    private function availableBookings(?Payment $payment = null)
    {
        return Booking::query()
            ->with(['customer', 'service'])
            ->when(
                $payment,
                fn ($query) => $query->where(fn ($bookingQuery) => $bookingQuery
                    ->whereDoesntHave('payment')
                    ->orWhereKey($payment->booking_id)),
                fn ($query) => $query->whereDoesntHave('payment')
            )
            ->orderBy('booking_code')
            ->get();
    }
}
