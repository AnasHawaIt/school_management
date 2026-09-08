<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'book_id'     => 'required|exists:books,id',
            'copy_id'     => 'nullable|exists:library_copies,id',
            'member_id'   => 'required|exists:members,id',
            'borrow_date' => 'required|date',
            'due_date'    => 'nullable|date|after_or_equal:borrow_date',
            'return_date' => 'nullable|date|after_or_equal:borrow_date',
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
