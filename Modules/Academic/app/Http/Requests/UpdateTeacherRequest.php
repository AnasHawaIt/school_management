<?php namespace Modules\Academic\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $teacher = \Modules\Academic\app\Entities\Teacher::findOrFail($this->route('teacher'));
        return [
            'first_name'        => 'sometimes|string|max:100',
            'last_name'         => 'sometimes|string|max:100',
            'first_name_ar'     => 'nullable|string|max:100',
            'last_name_ar'      => 'nullable|string|max:100',
            'email'             => 'sometimes|email|unique:users,email,' . $teacher->user_id,
            'password'          => 'nullable|string|min:8',
            'gender'            => 'sometimes|in:male,female',
            'date_of_birth'     => 'nullable|date|before:today',
            'national_id'       => 'nullable|string|unique:teachers,national_id,' . $teacher->id,
            'nationality'       => 'nullable|string|max:100',
            'phone'             => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'specialization'    => 'sometimes|string|max:150',
            'experience_years'  => 'nullable|integer|min:0|max:50',
            'joining_date'      => 'sometimes|date',
            'salary'            => 'nullable|numeric|min:0',
            'contract_type'     => 'nullable|in:full_time,part_time,temporary',
            'status'            => 'nullable|in:active,inactive,on_leave,resigned',
            'notes'             => 'nullable|string',
        ];
    }
}
