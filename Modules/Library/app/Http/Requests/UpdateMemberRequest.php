<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                'student_id' => 'sometimes|integer|exists:students,id',
                'username' => 'sometimes|string|unique:members,username',
                'password' => 'sometimes|string',
            ];
    }

    public function messages(): array
    {
        return [
            'student_id.integer' => 'student_id must be an integer',
            'school_id.integer' => 'school_id must be an integer',
        ];
    }
}
