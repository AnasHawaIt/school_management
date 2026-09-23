<?php

namespace Modules\Academic\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Name: minimum 3, maximum 20
            'first_name' => 'required|string|min:3|max:20',
            'last_name'  => 'required|string|min:3|max:20',

            'first_name_ar' => 'nullable|string|min:3|max:20',
            'last_name_ar'  => 'nullable|string|min:3|max:20',

            'email'    => 'required|email|unique:users,email',

            // Password: minimum 3, maximum 20
            'password' => 'required|string|min:3|max:20|confirmed',

            'gender'        => 'required|in:male,female',
            'date_of_birth' => 'nullable|date|before:today',
            'phone'        => 'nullable|string|min:3|max:20',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'user_type' => 'required|in:teacher',
            'is_active' => 'boolean',
            'address'    => 'nullable|string|min:3|max:100',
            'city'       => 'nullable|string|min:3|max:100',

            'national_id'       => 'nullable|string|unique:teachers,national_id',
            'specialization'    => 'required|string|max:150',
            'joining_date'      => 'required|date',
            'experience_years'  => 'nullable|integer|min:0|max:50',
            'salary'            => 'nullable|numeric|min:0',
            'contract_type'     => 'nullable|in:full_time,part_time,temporary',

            // Qualifications
            'qualifications'                  => 'nullable|array',
            'qualifications.*.type'           => 'required|in:degree,certificate,training,award',
            'qualifications.*.title'          => 'required|string|max:200',
            'qualifications.*.institution'    => 'required|string|max:200',
            'qualifications.*.year_obtained'  => 'required|digits:4|integer',
        ];
    }

    public function messages(): array
    {
        return [
            // First Name
            'first_name.required' => 'The first name is required.',
            'first_name.min'      => 'The first name must be at least 3 characters.',
            'first_name.max'      => 'The first name may not be greater than 20 characters.',

            // Last Name
            'last_name.required' => 'The last name is required.',
            'last_name.min'      => 'The last name must be at least 3 characters.',
            'last_name.max'      => 'The last name may not be greater than 20 characters.',

            // Password
            'password.required'  => 'The password field is required.',
            'password.min'       => 'The password must be at least 3 characters.',
            'password.max'       => 'The password may not be greater than 20 characters.',
            'password.confirmed' => 'The password confirmation does not match.',

            // Gender
            'gender.required' => 'Please select the gender.',
            'gender.in'       => 'The selected gender is invalid. Please choose Male or Female.',

            // Avatar
            'avatar.image' => 'The uploaded file must be an image.',
            'avatar.mimes' => 'The avatar must be a file of type: jpeg, png, jpg.',
            'avatar.max'   => 'The avatar size may not be greater than 2MB.',

            // General Email
            'email.required' => 'The email address is required.',
            'email.unique'   => 'This email has already been taken.',

            // Other fields
            'specialization.required' => 'The teacher specialization is required.',
            'joining_date.required'   => 'The joining date is required.',
        ];
    }
}
