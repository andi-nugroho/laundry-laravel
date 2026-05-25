<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Booking::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userIdRules = $this->user()?->isUser()
            ? ['required', 'integer', Rule::in([$this->user()->id])]
            : ['nullable', 'integer', Rule::exists('users', 'id')];

        $customerIdRules = $this->user()?->isUser()
            ? ['nullable', 'integer', Rule::exists('customers', 'id')->where('user_id', $this->user()->id)]
            : ['nullable', 'integer', Rule::exists('customers', 'id')];

        return [
            'booking_code' => ['prohibited'],
            'user_id' => $userIdRules,
            'customer_id' => $customerIdRules,
            'service_id' => ['required', 'integer', Rule::exists('services', 'id')],
            'booking_date' => ['required', 'date'],
            'estimated_finish_date' => ['prohibited'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'total_price' => ['prohibited'],
            'pickup_type' => ['sometimes', Rule::in(Booking::PICKUP_TYPES)],
            'status' => ['prohibited'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'booking_code.prohibited' => 'Kode booking dibuat otomatis oleh sistem.',
            'user_id.in' => 'Booking hanya boleh dibuat untuk akun Anda sendiri.',
            'customer_id.exists' => 'Customer yang dipilih tidak valid.',
            'service_id.required' => 'Layanan wajib dipilih.',
            'booking_date.required' => 'Tanggal booking wajib diisi.',
            'estimated_finish_date.prohibited' => 'Estimasi selesai dihitung otomatis oleh sistem.',
            'total_price.prohibited' => 'Total harga dihitung otomatis oleh sistem.',
            'pickup_type.in' => 'Tipe pickup tidak valid.',
            'status.prohibited' => 'Status booking dibuat otomatis oleh sistem.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->user()?->isUser() && ! $this->has('user_id')) {
            $this->merge([
                'user_id' => $this->user()->id,
            ]);
        }
    }
}
