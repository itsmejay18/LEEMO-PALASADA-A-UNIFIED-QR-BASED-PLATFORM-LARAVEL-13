<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('collect-payments');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vendor_id' => ['required', 'exists:vendors,id'],
            'amount_collected' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'collection_date' => ['required', 'date'],
            'proof_of_collection' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],
            'status' => ['nullable', 'in:pending,submitted,rejected'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
