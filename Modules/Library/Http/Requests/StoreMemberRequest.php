<?php

namespace Modules\Library\Http\Requests;

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
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-() ]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Member name is required',
            'email.required' => 'Email is required',
            'email.email'    => 'Invalid email format',
            'email.unique'   => 'Email already exists',
        ];
    }
}
