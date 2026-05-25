<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $customer = $this->route('customer');

        return $customer instanceof Customer
            && ($this->user()?->can('update', $customer) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userIdRules = $this->user()?->isUser()
            ? ['prohibited']
            : ['nullable', 'integer', Rule::exists('users', 'id')];

        return [
            'user_id' => $userIdRules,
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'gender' => ['nullable', Rule::in(Customer::GENDERS)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.prohibited' => 'User customer tidak boleh diubah.',
            'user_id.exists' => 'User yang dipilih tidak valid.',
            'name.required' => 'Nama customer wajib diisi.',
            'gender.in' => 'Gender customer tidak valid.',
        ];
    }
}
