<?php


namespace Modules\Messagings\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForwardMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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

    public function messages(): array
    {
        return [
            'recipients.required' => 'You must provide at least one recipient',
        ];
    }
}
