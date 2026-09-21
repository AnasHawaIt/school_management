<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionProgramRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'                     => 'required|string|max:200',
            'title_ar'                  => 'nullable|string|max:200',
            'section_id'                => 'required|exists:sections,id',
            'academic_year_id'          => 'required|exists:academic_years,id',
            'semester_id'               => 'required|exists:semesters,id',
            'inspection_date'           => 'required|date',
            'start_time'                => 'nullable|date_format:H:i',
            'end_time'                  => 'nullable|date_format:H:i|after:start_time',
            'type'                      => 'required|in:scheduled,surprise',
            'objectives'                => 'nullable|string',
            'notes'                     => 'nullable|string',
            'counselors'                => 'nullable|array',
            'counselors.*.counselor_id' => 'required|exists:counselors,id',
            'counselors.*.role'         => 'nullable|in:lead,member',
        ];
    }
}
