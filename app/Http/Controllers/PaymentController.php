<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        $data['payment_code'] = Payment::generatePaymentCode($data['payment_date']);
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

    public function pay(Payment $payment): View
    {
        Gate::authorize('payPayment', $payment);

        $payment->load(['booking.customer', 'booking.service']);

        return view('payments.pay', [
            'payment' => $payment,
            'paymentChannel' => $this->paymentChannel($payment),
            'paymentMethodLabel' => $this->paymentMethodLabel($payment),
        ]);
    }

    public function confirm(Request $request, Payment $payment): RedirectResponse
    {
        Gate::authorize('confirmPayment', $payment);

        $data = $request->validate([
            'payment_method' => ['nullable', 'string', 'in:qris,transfer,ewallet'],
        ]);

        $channel = $data['payment_method'] ?? $this->paymentChannel($payment);
        $paymentMethod = $channel === 'transfer' ? Payment::METHOD_TRANSFER : Payment::METHOD_EWALLET;

        $payment->update([
            'payment_method' => $paymentMethod,
            'amount_paid' => $payment->total_bill,
            'change_amount' => 0,
            'payment_status' => Payment::STATUS_PAID,
            'payment_date' => now(),
            'processed_by' => $request->user()->id,
            'notes' => $this->confirmedNotes($payment, $channel),
        ]);

        return redirect()
            ->route('user.orders.success', $payment->booking)
            ->with('success', 'Pembayaran berhasil')
            ->with('payment_success', 'Pembayaran berhasil');
    }

    public function invoice(Request $request, Payment $payment): Response
    {
        Gate::authorize('view', $payment);

        if ($request->user()->isUser() && $payment->payment_status !== Payment::STATUS_PAID) {
            abort(403);
        }

        $payment->load(['booking.customer', 'booking.service', 'processedBy']);
        $logoPath = public_path('logo.svg');
        $logoDataUri = file_exists($logoPath)
            ? 'data:image/svg+xml;base64,'.base64_encode((string) file_get_contents($logoPath))
            : null;

        return Pdf::loadView('payments.invoice', [
            'payment' => $payment,
            'logoDataUri' => $logoDataUri,
        ])
            ->setPaper([0, 0, 226.77, 620], 'portrait')
            ->download("nota-{$payment->payment_code}.pdf");
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
                    ->orWhere('id', $payment->booking_id)),
                fn ($query) => $query->whereDoesntHave('payment')
            )
            ->orderBy('booking_code')
            ->get();
    }

    private function paymentChannel(Payment $payment): string
    {
        $notes = strtolower((string) $payment->notes);

        if (str_contains($notes, 'payment_channel=qris') || str_contains($notes, 'qris')) {
            return 'qris';
        }

        if (str_contains($notes, 'payment_channel=transfer') || $payment->payment_method === Payment::METHOD_TRANSFER) {
            return 'transfer';
        }

        if (str_contains($notes, 'payment_channel=ewallet')) {
            return 'ewallet';
        }

        if (str_contains($notes, 'payment_channel=cod') || $payment->payment_method === Payment::METHOD_CASH) {
            return 'cod';
        }

        return $payment->payment_method === Payment::METHOD_EWALLET ? 'qris' : $payment->payment_method;
    }

    private function paymentMethodLabel(Payment $payment): string
    {
        return match ($this->paymentChannel($payment)) {
            'qris' => 'QRIS',
            'transfer' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
            'cod' => 'COD / Bayar di Tempat',
            default => ucfirst($payment->payment_method),
        };
    }

    private function confirmedNotes(Payment $payment, string $channel): string
    {
        $label = match ($channel) {
            'qris' => 'QRIS',
            'transfer' => 'Transfer Bank BCA',
            'ewallet' => 'E-Wallet',
            default => strtoupper($channel),
        };

        return trim("payment_channel={$channel}; Pembayaran customer dikonfirmasi via {$label}");
    }
}
