<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimetableRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'section_id'       => 'required|exists:sections,id',
            'subject_id'       => 'required|exists:subjects,id',
            'teacher_id'       => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id'      => 'required|exists:semesters,id',
            'day_of_week'      => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'period_number'    => 'required|integer|min:1|max:12',
            'start_time'       => 'required|date_format:H:i:s',
            'end_time'         => 'required|date_format:H:i:s|after:start_time',
            'room_number'      => 'nullable|string|max:20',//A1
        ];
    }
}
