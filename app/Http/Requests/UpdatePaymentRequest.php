<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $payment = $this->route('payment');

        return $payment instanceof Payment
            && ($this->user()?->can('update', $payment) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $payment = $this->route('payment');
        $paymentId = $payment instanceof Payment ? $payment->id : $payment;

        return [
            'booking_id' => [
                'sometimes',
                'integer',
                Rule::exists('bookings', 'id'),
                Rule::unique('payments', 'booking_id')->ignore($paymentId),
            ],
            'payment_code' => ['prohibited'],
            'payment_date' => ['sometimes', 'date'],
            'payment_method' => ['sometimes', Rule::in(Payment::METHODS)],
            'amount_paid' => ['sometimes', 'numeric', 'min:0', 'max:9999999999.99'],
            'total_bill' => ['prohibited'],
            'change_amount' => ['prohibited'],
            'payment_status' => ['prohibited'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'processed_by' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'booking_id.unique' => 'Booking ini sudah memiliki data pembayaran.',
            'payment_code.prohibited' => 'Kode pembayaran tidak boleh diubah.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'total_bill.prohibited' => 'Total tagihan diambil otomatis dari booking.',
            'change_amount.prohibited' => 'Kembalian dihitung otomatis oleh sistem.',
            'payment_status.prohibited' => 'Status pembayaran dihitung otomatis oleh sistem.',
            'processed_by.prohibited' => 'Petugas pembayaran diisi otomatis oleh sistem.',
        ];
    }
}
