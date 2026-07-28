<?php

namespace Modules\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'title' => ['required', 'string', 'max:255'],

            'body' => ['required', 'string'],

            'type' => ['required', 'string', 'max:100'],

            'data' => ['nullable', 'array'],

            'target' => [
                'required',
                Rule::in(['users', 'role', 'all'])
            ],

            'users' => [
                'required_if:target,users',
                'array'
            ],

            'users.*' => [
                'exists:users,id'
            ],

            'role' => [
                'required_if:target,role',
                Rule::in([
                    'admin',
                    'teacher',
                    'student',
                    'parent'
                ])
            ],

        ];
    }
}
