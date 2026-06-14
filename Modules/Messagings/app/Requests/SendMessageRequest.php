<?php

namespace Modules\Messagings\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => [
                'required',
                'string',
                'max:255'
            ],

            'body' => [
                'required',
                'string'
            ],

            'priority' => [
                'nullable',
                'in:normal,important,urgent'
            ],

            'recipients' => [
                'required',
                'array',
                'min:1'
            ],

            'recipients.*' => [
                'exists:users,id'
            ]
        ];
    }

    public function messages(): array{
        return [
            'subject.required' => 'Subject is required',
            'body.required' => 'Body is required',
            'priority.required' => 'Priority is required',
            'recipients.required' => 'Recipients is required',
            'body.string' => 'Body must be string',
        ];
    }
}
