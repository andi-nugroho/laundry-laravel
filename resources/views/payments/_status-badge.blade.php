@php
    $payment = $payment ?? null;
    $notes = strtolower((string) ($payment?->notes ?? ''));
    $isCashUnpaid = $payment
        && $payment->payment_status === \App\Models\Payment::STATUS_UNPAID
        && ($payment->payment_method === \App\Models\Payment::METHOD_CASH || str_contains($notes, 'payment_channel=cod'));
    $isWaitingConfirmation = $payment
        && $payment->payment_status === \App\Models\Payment::STATUS_UNPAID
        && ! $isCashUnpaid
        && (
            $payment->payment_method !== \App\Models\Payment::METHOD_CASH
            || str_contains($notes, 'payment_channel=qris')
            || str_contains($notes, 'payment_channel=transfer')
            || str_contains($notes, 'payment_channel=ewallet')
        );

    if ($isCashUnpaid) {
        $status = 'cash_unpaid';
    } elseif ($isWaitingConfirmation && ($status ?? null) === \App\Models\Payment::STATUS_UNPAID) {
        $status = 'pending_confirmation';
    }

    $statusClasses = [
        'unpaid' => 'bg-red-100 text-red-800',
        'cash_unpaid' => 'bg-amber-100 text-amber-800',
        'waiting_confirmation' => 'bg-amber-100 text-amber-800',
        'pending_confirmation' => 'bg-amber-100 text-amber-800',
        'partial' => 'bg-amber-100 text-amber-800',
        'paid' => 'bg-green-100 text-green-800',
    ];

    $statusLabels = [
        'unpaid' => 'Belum Dibayar',
        'cash_unpaid' => 'Bayar di Tempat',
        'waiting_confirmation' => 'Menunggu Konfirmasi',
        'pending_confirmation' => 'Menunggu Konfirmasi',
        'partial' => 'Partial',
        'paid' => 'Lunas',
    ];

    $displayStatus = $displayStatus ?? ($statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)));
@endphp

<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-700' }}">
    {{ $displayStatus }}
</span>
