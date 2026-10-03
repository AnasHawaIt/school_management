<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'first_name_ar' => 'nullable|string|max:100',
            'last_name_ar' => 'nullable|string|max:100',

            'email' => 'sometimes|required|email|unique:users,email,' . $userId,

            'password' => 'nullable|string|min:8|confirmed',

            'phone' => 'nullable|string|max:20',
            'gender' => 'sometimes|required|in:male,female',
            'date_of_birth' => 'nullable|date|before:today',


            'avatar' => 'nullable|string',
            'user_type' => 'sometimes|required|in:admin,teacher,student,parent',
            'is_active' => 'boolean',
            'role' => 'nullable|string|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [

            'first_name.required' => 'First name is required',
            'first_name.string'   => 'First name must be a valid string',
            'first_name.max'      => 'First name cannot exceed 100 characters',

            'last_name.required'  => 'Last name is required',
            'last_name.string'    => 'Last name must be a valid string',
            'last_name.max'       => 'Last name cannot exceed 100 characters',


            'first_name_ar.max'   => 'Arabic first name cannot exceed 100 characters',
            'last_name_ar.max'    => 'Arabic last name cannot exceed 100 characters',


            'email.required'      => 'Email address is required',
            'email.email'         => 'Please enter a valid email address',
            'email.unique'        => 'This email is already registered in our system',


            'password.required'   => 'Password is required',
            'password.min'        => 'Password must be at least 8 characters',
            'password.confirmed'  => 'Password confirmation does not match',


            'gender.required'     => 'Please select a gender',
            'gender.in'           => 'Selected gender is invalid',
            'date_of_birth.date'  => 'Date of birth must be a valid date',
            'phone.max'           => 'Phone number cannot exceed 20 characters',


            'user_type.required'  => 'User type is required',
            'user_type.in'        => 'The selected user type is invalid',
            'role.exists'         => 'The selected role does not exist',
        ];
    }
}
