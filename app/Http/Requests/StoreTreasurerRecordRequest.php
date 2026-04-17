<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTreasurerRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('verify-collections');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'collection_id' => ['required', 'exists:collections,id'],
            'amount_verified' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'verification_date' => ['required', 'date'],
            'official_receipt_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('treasurer_records', 'official_receipt_number'),
            ],
            'collection_status' => ['required', 'in:verified,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
