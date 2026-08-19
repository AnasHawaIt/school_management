<?php


namespace Modules\Activities\app\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'participant_type' => [
                'required',
                'string',
                Rule::in([
                    'student',
                    'teacher',
                    'parent',
                ]),
            ],

            'participant_id' => [
                'required',
                'integer',
                'min:1',
            ],

            'role' => [
                'nullable',
                'string',
                'max:30',
            ],
        ];
    }
}
