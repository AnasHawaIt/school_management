<?php


namespace Modules\Activities\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'sometimes',
                'integer',
                'exists:activity_categories,id',
            ],

            'title' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'title_ar' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'description_ar' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'location' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],

            'start_at' => [
                'sometimes',
                'date',
            ],

            'end_at' => [
                'sometimes',
                'date',
                'after:start_at',
            ],

            'capacity' => [
                'sometimes',
                'nullable',
                'integer',
                'min:1',
            ],

            'registration_required' => [
                'sometimes',
                'boolean',
            ],

            'registration_deadline' => [
                'sometimes',
                'nullable',
                'date',
            ],

            'is_featured' => [
                'sometimes',
                'boolean',
            ],

            'notes' => [
                'sometimes',
                'nullable',
                'string',
            ],
        ];
    }
}
