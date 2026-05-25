<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Customer::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userIdRules = $this->user()?->isUser()
            ? ['required', 'integer', Rule::in([$this->user()->id])]
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
            'user_id.in' => 'Customer hanya boleh dibuat untuk akun Anda sendiri.',
            'user_id.exists' => 'User yang dipilih tidak valid.',
            'name.required' => 'Nama customer wajib diisi.',
            'gender.in' => 'Gender customer tidak valid.',
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
