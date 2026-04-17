<?php

namespace App\Http\Requests;

use App\Models\Vendor;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('create', Vendor::class);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vendor_name' => ['required', 'string', 'max:255'],
            'stall_number' => [
                'required',
                'string',
                'max:25',
                Rule::unique('vendors', 'stall_number'),
            ],
            'contact_number' => ['required', 'string', 'max:30'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('vendors', 'email'),
            ],
            'logo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
