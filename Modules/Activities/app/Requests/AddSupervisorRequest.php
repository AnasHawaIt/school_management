<?php


namespace Modules\Activities\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddSupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'teacher_id' => [
                'required',
                'integer',
                'exists:teachers,id',
            ],

            'role' => [
                'nullable',
                'string',
                'max:100',
            ],

            'is_primary' => [
                'sometimes',
                'boolean',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
