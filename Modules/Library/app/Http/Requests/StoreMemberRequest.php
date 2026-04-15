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
            'school_id' => 'required|integer|exists:schools,id',
            'username' => 'required|string|unique:members,username',
            'password' => 'required|string|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
           'student_id.required' => 'Student ID is required.',
            'student_id.integer' => 'Student ID must be an integer.',
            'student_id.exists' => 'Student ID does not exist.',
            'school_id.required' => 'School ID is required.',
            'school_id.integer' => 'School ID must be an integer.',
            'school_id.exists' => 'School ID does not exist.',
            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a string.',
            'username.unique' => 'Username already exists.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.confirmed' => 'Password does not match.',
        ];
    }
}
