<?php

namespace Modules\Examination\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'             => 'required|string|max:150',
            'name_ar'          => 'nullable|string|max:150',
            'exam_type_id'     => 'required|exists:exam_types,id',
            'subject_id'       => 'required|exists:subjects,id',
            'section_id'       => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id'      => 'required|exists:semesters,id',
            'teacher_id'       => 'required|exists:teachers,id',
            'exam_date'        => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:50',
            'total_marks'      => 'required|numeric|min:1|max:999',
            'pass_marks'       => 'required|numeric|min:1|lte:total_marks',
            'instructions'     => 'nullable|string',
        ];
    }
}
