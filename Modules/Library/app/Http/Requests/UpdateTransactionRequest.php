<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id'     => 'sometimes|exists:books,id',
            'member_id'   => 'sometimes|exists:members,id',
            'borrow_date' => 'sometimes|date',
            'due_date'    => 'sometimes|date|after_or_equal:borrow_date',
            'status'      => 'sometimes|in:borrowed,returned',
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status must be borrowed or returned',
        ];
    }
}
