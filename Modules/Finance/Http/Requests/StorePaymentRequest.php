<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_fee_id'     => 'required|integer|exists:student_fees,id',
            'amount'             => 'required|numeric|min:0.01',
            'method'             => 'required|in:cash,bank_transfer,stripe,paypal',
            'paid_at'            => 'nullable|date',
            'notes'              => 'nullable|string',
            // Bank transfer
            'bank_name'          => 'required_if:method,bank_transfer|nullable|string',
            'transfer_reference' => 'required_if:method,bank_transfer|nullable|string',
            'transfer_date'      => 'required_if:method,bank_transfer|nullable|date',
            // Online
            'transaction_id'     => 'required_if:method,stripe,paypal|nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'student_fee_id.required'     => 'Student fee is required',
            'student_fee_id.exists'       => 'Student fee not found',
            'amount.required'             => 'Payment amount is required',
            'amount.min'                  => 'Amount must be greater than 0',
            'method.required'             => 'Payment method is required',
            'method.in'                   => 'Invalid payment method',
            'bank_name.required_if'       => 'Bank name is required for bank transfer',
            'transfer_reference.required_if' => 'Transfer reference is required for bank transfer',
            'transaction_id.required_if'  => 'Transaction ID is required for online payments',
        ];
    }
}
