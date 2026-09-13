<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Library\app\Enums\BookCopiesStatus;

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
            'copy_id'     => [
                'nullable',
                Rule::exists('library_copies', 'id')->where(function ($query) {
                    $query->where('book_id', $this->input('book_id'))
                        ->where('status', BookCopiesStatus::AVAILABLE);
                }),
            ],
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
