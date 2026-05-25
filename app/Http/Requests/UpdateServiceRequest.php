<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $serviceId = $this->route('service')?->id ?? $this->route('service');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services', 'name')->ignore($serviceId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'price_per_kg' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'estimated_days' => ['required', 'integer', 'min:1', 'max:365'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama layanan wajib diisi.',
            'name.unique' => 'Nama layanan sudah digunakan.',
            'price_per_kg.required' => 'Harga per kg wajib diisi.',
            'price_per_kg.min' => 'Harga per kg tidak boleh negatif.',
            'estimated_days.required' => 'Estimasi hari wajib diisi.',
            'estimated_days.min' => 'Estimasi hari minimal 1 hari.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
