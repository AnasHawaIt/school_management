<?php

namespace Modules\Messagings\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => [
                'required',
                'string',
                'max:10000',
            ],
        ];
    }

    public function messages(): array{
        return [
            'body.required' => 'Please enter a message.',
            'body.string' =>'body must be a string'
        ];
    }
}
