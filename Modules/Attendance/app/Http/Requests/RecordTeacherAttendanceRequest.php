<?php

namespace Modules\Attendance\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordTeacherAttendanceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'teacher_id'     => 'required|exists:teachers,id',
            'status_id'      => 'required|exists:attendance_statuses,id',
            'date'           => 'required|date|before_or_equal:today',
            'check_in_time'  => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after:check_in_time',
            'late_minutes'   => 'nullable|integer|min:0|max:120',
            'notes'          => 'nullable|string|max:500',
        ];
    }
}
