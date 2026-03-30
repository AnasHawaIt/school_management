<?php

namespace Modules\School\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'grade_id' => 'sometimes|required|exists:grades,id',
            'academic_year_id' => 'sometimes|required|exists:academic_years,id',
            'name' => 'sometimes|required|string|max:255',
            'max_students' => 'sometimes|required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
