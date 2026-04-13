<?php namespace Modules\Academic\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreGuardianRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'first_name'                      => 'required|string|max:100',
            'last_name'                       => 'required|string|max:100',
            'first_name_ar'                   => 'nullable|string|max:100',
            'last_name_ar'                    => 'nullable|string|max:100',
            'email'                           => 'required|email|unique:users,email',
            'password'                        => 'nullable|string|min:8',
            'gender'                          => 'required|in:male,female',
            'national_id'                     => 'nullable|string|unique:parents,national_id',
            'nationality'                     => 'nullable|string|max:100',
            'phone'                           => 'required|string|max:20',
            'phone_secondary'                 => 'nullable|string|max:20',
            'address'                         => 'nullable|string|max:255',
            'city'                            => 'nullable|string|max:100',
            'occupation'                      => 'nullable|string|max:100',
            'employer'                        => 'nullable|string|max:150',
            'work_phone'                      => 'nullable|string|max:20',
            'date_of_birth'                   => 'required|date|before:today',
            'avatar'                          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'education_level'                 => 'nullable|in:none,primary,secondary,diploma,bachelor,master,phd',
            'notes'                           => 'nullable|string',
            'students'                        => 'nullable|array',
            'students.*.student_id'           => 'required|exists:students,id',
            'students.*.relationship'         => 'required|in:father,mother,guardian,other',
            'students.*.is_primary_contact'   => 'nullable|boolean',
            'students.*.can_pickup'           => 'nullable|boolean',
        ];
    }
}
