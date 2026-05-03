<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $target = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($target?->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(['Admin', 'Manager', 'Collector', 'Treasurer', 'Vendor', 'Customer'])],
            'vendor_id' => ['nullable', 'exists:vendors,id', 'required_if:role,Vendor'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
