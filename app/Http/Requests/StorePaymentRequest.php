<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Payment::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'integer', Rule::exists('bookings', 'id'), Rule::unique('payments', 'booking_id')],
            'payment_code' => ['prohibited'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', Rule::in(Payment::METHODS)],
            'amount_paid' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'total_bill' => ['prohibited'],
            'change_amount' => ['prohibited'],
            'payment_status' => ['prohibited'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'processed_by' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'booking_id.required' => 'Booking wajib dipilih.',
            'booking_id.unique' => 'Booking ini sudah memiliki data pembayaran.',
            'payment_code.prohibited' => 'Kode pembayaran dibuat otomatis oleh sistem.',
            'payment_date.required' => 'Tanggal pembayaran wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'amount_paid.required' => 'Nominal pembayaran wajib diisi.',
            'total_bill.prohibited' => 'Total tagihan diambil otomatis dari booking.',
            'change_amount.prohibited' => 'Kembalian dihitung otomatis oleh sistem.',
            'payment_status.prohibited' => 'Status pembayaran dihitung otomatis oleh sistem.',
            'processed_by.prohibited' => 'Petugas pembayaran diisi otomatis oleh sistem.',
        ];
    }
}
