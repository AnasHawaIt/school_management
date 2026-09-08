<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'book_id'     => 'sometimes|exists:books,id',
            'copy_id'     => 'sometimes|nullable|exists:library_copies,id',
            'member_id'   => 'sometimes|exists:members,id',
            'borrow_date' => 'sometimes|date',
            'due_date'    => 'sometimes|nullable|date|after_or_equal:borrow_date',
            'return_date' => 'sometimes|nullable|date|after_or_equal:borrow_date',
            'returned_at' => 'sometimes|nullable|date',
            'status'      => 'sometimes|in:borrowed,returned,late',
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.exists' => 'The book id does not exist.',
            'member_id.exists' => 'The member id does not exist.',
            'borrow_date.date' => 'The borrow date is not a valid date.',
            'return_date.date' => 'The return date is not a valid date.',
        ];
    }
}
