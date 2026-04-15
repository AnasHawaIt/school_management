<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id'     => 'required|exists:books,id',
            'member_id'   => 'required|exists:members,id',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.exists'   => 'Book not found',
            'member_id.exists' => 'Member not found',
            'borrow_date.date'   => 'Borrow date must be a date',
            'return_date.date'   => 'Return date must be a date',
        ];
    }
}
