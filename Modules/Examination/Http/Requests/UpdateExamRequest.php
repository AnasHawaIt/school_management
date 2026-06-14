<?php

namespace Modules\Examination\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'         => 'sometimes|string|max:150',
            'name_ar'      => 'nullable|string|max:150',
            'exam_type_id' => 'sometimes|exists:exam_types,id',
            'teacher_id'   => 'sometimes|exists:teachers,id',
            'exam_date'    => 'sometimes|date',
            'start_time'   => 'sometimes|date_format:H:i',
            'end_time'     => 'sometimes|date_format:H:i|after:start_time',
            'room'         => 'nullable|string|max:50',
            'total_marks'  => 'sometimes|numeric|min:1|max:999',
            'pass_marks'   => 'sometimes|numeric|min:1|lte:total_marks',
            'instructions' => 'nullable|string',
        ];
    }
}
