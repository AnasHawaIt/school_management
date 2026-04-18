<?php

namespace Modules\Attendance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkRecordAttendanceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'section_id'              => 'required|exists:sections,id',
            'academic_year_id'        => 'required|exists:academic_years,id',
            'semester_id'             => 'required|exists:semesters,id',
            'date'                    => 'required|date|before_or_equal:today',
            'records'                 => 'required|array|min:1',
            'records.*.student_id'    => 'required|exists:students,id',
            'records.*.status_id'     => 'required|exists:attendance_statuses,id',
            'records.*.check_in_time' => 'nullable|date_format:H:i',
            'records.*.late_minutes'  => 'nullable|integer|min:0|max:120',
            'records.*.notes'         => 'nullable|string|max:500',
        ];
    }
}
