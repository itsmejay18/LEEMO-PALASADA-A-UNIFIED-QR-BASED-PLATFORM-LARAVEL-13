<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('manage-users');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', Rule::in(['Admin', 'Manager', 'Collector', 'Treasurer', 'Vendor', 'Customer'])],
            'vendor_id' => ['nullable', 'exists:vendors,id', 'required_if:role,Vendor'],
        ];
    }
}
