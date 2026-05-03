<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $vendor = $this->route('vendor');

        return $vendor ? (bool) $this->user()?->can('update', $vendor) : false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $vendorId = $this->route('vendor')?->id;

        return [
            'vendor_name' => ['required', 'string', 'max:255'],
            'stall_number' => [
                'required',
                'string',
                'max:25',
                Rule::unique('vendors', 'stall_number')->ignore($vendorId),
            ],
            'contact_number' => ['required', 'string', 'max:30'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('vendors', 'email')->ignore($vendorId),
            ],
            'category' => ['nullable', 'string', 'max:100'],
            'contract_start_date' => ['nullable', 'date'],
            'contract_end_date' => ['nullable', 'date'],
            'monthly_rent' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
