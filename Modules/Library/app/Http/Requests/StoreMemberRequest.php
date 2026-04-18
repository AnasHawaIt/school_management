<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
           'student_id' => 'required|integer|exists:students,id',
            'username' => 'required|string|unique:members,username',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
           'student_id.required' => 'Student ID is required.',
            'student_id.integer' => 'Student ID must be an integer.',
            'student_id.exists' => 'Student ID does not exist.',
            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a string.',
            'username.unique' => 'Username already exists.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
        ];
    }
}
