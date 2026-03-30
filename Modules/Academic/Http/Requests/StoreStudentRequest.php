<?php namespace Modules\Academic\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'first_name'                       => 'required|string|max:100',
            'last_name'                        => 'required|string|max:100',
            'first_name_ar'                    => 'nullable|string|max:100',
            'last_name_ar'                     => 'nullable|string|max:100',
            'email'                            => 'required|email|unique:users,email',
            'password'                         => 'nullable|string|min:8',
            'gender'                           => 'required|in:male,female',
            'date_of_birth'                    => 'required|date|before:today',
            'national_id'                      => 'nullable|string|unique:students,national_id',
            'nationality'                      => 'nullable|string|max:100',
            'place_of_birth'                   => 'nullable|string|max:150',
            'religion'                         => 'nullable|string|max:50',
            'address'                          => 'nullable|string|max:255',
            'city'                             => 'nullable|string|max:100',
            'phone'                            => 'nullable|string|max:20',
            'enrollment_date'                  => 'required|date',
            'current_section_id'               => 'nullable|exists:sections,id',
            'academic_year_id'                 => 'nullable|exists:academic_years,id',
            'previous_school'                  => 'nullable|string|max:200',
            'blood_type'                       => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'notes'                            => 'nullable|string',
            'medical_record'                   => 'nullable|array',
            'medical_record.chronic_diseases'  => 'nullable|string',
            'medical_record.allergies'         => 'nullable|string',
            'medical_record.medications'       => 'nullable|string',
            'medical_record.disabilities'      => 'nullable|string',
            'medical_record.special_needs'     => 'nullable|string',
            'medical_record.doctor_name'       => 'nullable|string|max:100',
            'medical_record.doctor_phone'      => 'nullable|string|max:20',
            'medical_record.insurance_number'  => 'nullable|string|max:50',
            'medical_record.insurance_company' => 'nullable|string|max:100',
        ];
    }
}
