<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Library\Entities\Borrowing;

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
            'copy_id'     => [
                'sometimes',
                'nullable',
                Rule::exists('library_copies', 'id')->where(function ($query) {
                    $transaction = Borrowing::find($this->route('id'));
                    $bookId = $this->input('book_id', $transaction?->book_id);

                    $query->where('book_id', $bookId)
                        ->whereIn('status', ['available', 'borrowed']);
                }),
            ],
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
