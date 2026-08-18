<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_fee_id' => ['required', 'integer', 'exists:student_fees,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', 'in:cash,bank_transfer,stripe,paypal'],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'bank_name' => ['required_if:method,bank_transfer', 'nullable', 'string', 'max:255'],
            'transfer_reference' => ['required_if:method,bank_transfer', 'nullable', 'string', 'max:255'],
            'transfer_date' => ['required_if:method,bank_transfer', 'nullable', 'date'],
            'transaction_id' => ['required_if:method,stripe,paypal', 'nullable', 'string', 'max:255'],
        ];
    }
}
