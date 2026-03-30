<?php namespace Modules\Academic\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'first_name_ar'     => 'nullable|string|max:100',
            'last_name_ar'      => 'nullable|string|max:100',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'nullable|string|min:8',
            'gender'            => 'required|in:male,female',
            'date_of_birth'     => 'nullable|date|before:today',
            'national_id'       => 'nullable|string|unique:teachers,national_id',
            'nationality'       => 'nullable|string|max:100',
            'phone'             => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'specialization'    => 'required|string|max:150',
            'experience_years'  => 'nullable|integer|min:0|max:50',
            'joining_date'      => 'required|date',
            'salary'            => 'nullable|numeric|min:0',
            'contract_type'     => 'nullable|in:full_time,part_time,temporary',
            'notes'             => 'nullable|string',
            'qualifications'                  => 'nullable|array',
            'qualifications.*.type'           => 'required|in:degree,certificate,training,award',
            'qualifications.*.title'          => 'required|string|max:200',
            'qualifications.*.institution'    => 'required|string|max:200',
            'qualifications.*.field_of_study' => 'nullable|string|max:150',
            'qualifications.*.year_obtained'  => 'required|digits:4|integer',
            'qualifications.*.expiry_date'    => 'nullable|date',
        ];
    }
}
