<?php

namespace Modules\Core\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
          return [
              'first_name'    => 'required|string|max:255',
              'last_name'     => 'required|string|max:255',
              'first_name_ar' => 'nullable|string|max:255',
              'last_name_ar'  => 'nullable|string|max:255',
              'gender'        => 'required|in:male,female',
              'date_of_birth' => 'required|date',
              'email'         => 'required|email|unique:users,email',
              'password'      => 'required|string|min:8|confirmed',
              'user_type'     => 'required|in:admin,teacher,student,parent',
              'avatar'        => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
              'phone'         => 'nullable|string|max:255',
              'national_id'   => 'nullable|string|unique:teachers,national_id',
          ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'user_type.required' => 'User type is required',
            'user_type.in' => 'Invalid user type',
        ];
    }
}
