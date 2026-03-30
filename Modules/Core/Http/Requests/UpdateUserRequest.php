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
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|string',
            'user_type' => 'sometimes|required|in:admin,teacher,student,parent',
            'is_active' => 'boolean',
            'role' => 'nullable|string|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be valid',
            'email.unique' => 'This email is already taken',
            'password.min' => 'Password must be at least 8 characters',
            'user_type.required' => 'User type is required',
            'user_type.in' => 'Invalid user type',
            'role.exists' => 'Selected role does not exist',
        ];
    }
}
