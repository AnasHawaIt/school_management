<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeeStructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fee_type_id'      => 'sometimes|required|exists:fee_types,id',
            'academic_year_id' => 'sometimes|required|exists:academic_years,id',
            'grade_id'         => 'nullable|exists:grades,id',
            'section_id'         => 'nullable|exists:sections,id',
            'amount'           => 'sometimes|required|numeric|min:0',
            'frequency'        => 'sometimes|required|in:once,monthly,semester,annual',
            'due_date'         => 'nullable|date',
            'notes'            => 'nullable|string',
            'is_active'        => 'boolean',
        ];
    }
}
