<?php

namespace Modules\Library\Http\Requests;

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
            'due_date'    => 'required|date|after_or_equal:borrow_date',
            //'status'      => 'nullable|in:borrowed,returned',
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.exists'   => 'Book not found',
            'member_id.exists' => 'Member not found',
            'due_date.after_or_equal' => 'Due date must be after borrow date',
        ];
    }
}
