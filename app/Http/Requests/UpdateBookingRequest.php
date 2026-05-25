<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $booking = $this->route('booking');

        return $booking instanceof Booking
            && ($this->user()?->can('update', $booking) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userIdRules = $this->user()?->isUser()
            ? ['prohibited']
            : ['sometimes', 'nullable', 'integer', Rule::exists('users', 'id')];

        $customerIdRules = $this->user()?->isUser()
            ? ['sometimes', 'nullable', 'integer', Rule::exists('customers', 'id')->where('user_id', $this->user()->id)]
            : ['sometimes', 'nullable', 'integer', Rule::exists('customers', 'id')];

        $statusRules = $this->user()?->isUser()
            ? ['prohibited']
            : ['sometimes', Rule::in(Booking::STATUSES)];

        return [
            'booking_code' => ['prohibited'],
            'user_id' => $userIdRules,
            'customer_id' => $customerIdRules,
            'service_id' => ['sometimes', 'integer', Rule::exists('services', 'id')],
            'booking_date' => ['sometimes', 'date'],
            'estimated_finish_date' => ['prohibited'],
            'weight' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:999999.99'],
            'total_price' => ['prohibited'],
            'pickup_type' => ['sometimes', Rule::in(Booking::PICKUP_TYPES)],
            'status' => $statusRules,
            'notes' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'booking_code.prohibited' => 'Kode booking tidak boleh diubah.',
            'user_id.prohibited' => 'User booking tidak boleh diubah.',
            'customer_id.exists' => 'Customer yang dipilih tidak valid.',
            'service_id.exists' => 'Layanan yang dipilih tidak valid.',
            'estimated_finish_date.prohibited' => 'Estimasi selesai dihitung otomatis oleh sistem.',
            'total_price.prohibited' => 'Total harga dihitung otomatis oleh sistem.',
            'pickup_type.in' => 'Tipe pickup tidak valid.',
            'status.prohibited' => 'Status booking tidak boleh diubah oleh user.',
            'status.in' => 'Status booking tidak valid.',
        ];
    }
}
