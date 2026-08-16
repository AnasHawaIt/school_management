<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignFeeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_id'       => 'required|integer|exists:students,id',
            'fee_structure_id' => 'required|integer|exists:fee_structures,id',
            'discount_id'      => 'nullable|integer|exists:discounts,id',
            'due_date'         => 'nullable|date',
            'notes'            => 'nullable|string',
        ];
    }
}
