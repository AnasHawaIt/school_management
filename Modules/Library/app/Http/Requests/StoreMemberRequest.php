<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
           'user_id' => 'required|integer|exists:users,id|unique:members,user_id',
            'membership_number' => 'required|unique:members,membership_number',

        ];
    }

    public function messages(): array
    {
        return [
            'membership_number.unique' => 'Membership number already exists',
            'membership_number.required' => 'Membership number is required',
            'user_id.required' => 'User is required',
            'user_id.integer' => 'User must be an integer',
            'user_id.exists' => 'User does not exist',
        ];
    }
}
