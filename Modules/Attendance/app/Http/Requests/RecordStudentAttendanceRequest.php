<?php

namespace Modules\Attendance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordStudentAttendanceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_id'       => 'required|exists:students,id',
            'section_id'       => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id'      => 'required|exists:semesters,id',
            'status_id'        => 'required|exists:attendance_statuses,id',
            'date'             => 'required|date|before_or_equal:today',
            'check_in_time'    => 'nullable|date_format:H:i',
            'late_minutes'     => 'nullable|integer|min:0|max:120',
            'notes'            => 'nullable|string|max:500',
        ];
    }
}
