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
            'conversation_id'=>[
                'required',
                'integer',
                'exists:conversations,id'],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'body' => [
                'required',
                'string',
            ],

            'priority' => [
                'nullable',
                'in:normal,important,urgent',
            ],

            'recipients' => [
                'required',
                'array',
                'min:1',
            ],

            'recipients.*' => [
                'integer',
                'distinct',
                'exists:users,id',
            ],
        ];
    }

    public function messages(): array{
        return [
            'sender_id'=>'sender_id is required ',
            'sender_id.integer'=>'sender_id must be an integer',
            'subject.required' => 'Subject is required',
            'body.required' => 'Body is required',
            'priority.required' => 'Priority is required',
            'body.string' => 'Body must be string',
        ];
    }
}
