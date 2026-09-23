<?php namespace Modules\Academic\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $student = \Modules\Academic\app\Entities\Student::findOrFail($this->route('student'));
        return [
            'first_name'         => 'sometimes|string|max:100',
            'last_name'          => 'sometimes|string|max:100',
            'email'              => 'sometimes|email|unique:users,email,' . $student->user_id,
            'password'           => 'nullable|string|min:8',
            'gender'             => 'sometimes|in:male,female',
            'date_of_birth'      => 'sometimes|date|before:today',
            'national_id'        => 'nullable|string|unique:students,national_id,' . $student->id,
            'nationality'        => 'nullable|string|max:100',
            'address'            => 'nullable|string|max:255',
            'city'               => 'nullable|string|max:100',
            'phone'              => 'nullable|string|max:20',
            'enrollment_date'    => 'sometimes|date',
            'current_section_id' => 'nullable|exists:sections,id',
            'academic_year_id'   => 'nullable|exists:academic_years,id',
            'blood_type'         => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'status'             => 'nullable|in:active,inactive,transferred,graduated,expelled',
            'notes'              => 'nullable|string',
        ];
    }
}
