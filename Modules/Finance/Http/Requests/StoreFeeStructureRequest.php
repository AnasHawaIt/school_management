<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeeStructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fee_type_id'      => 'required|exists:fee_types,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id'         => 'nullable|exists:grades,id',
            'section_id'         => 'nullable|exists:sections,id',
            'amount'           => 'required|numeric|min:0',
            'frequency'        => 'required|in:once,monthly,semester,annual',
            'due_date'         => 'nullable|date',
            'notes'            => 'nullable|string',
            'is_active'        => 'boolean',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $exists = \Modules\Finance\Entities\FeeStructure::where('fee_type_id', $this->fee_type_id)
                ->where('academic_year_id', $this->academic_year_id)
                ->where('grade_id', $this->grade_id)
                ->where('section_id', $this->class_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add('fee_type_id', 'A fee structure for this type, academic year, grade, and class already exists.');
            }
        });
    }
}
