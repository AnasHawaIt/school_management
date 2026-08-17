<?php


namespace Modules\Activities\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                'exists:activity_categories,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'title_ar' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'description_ar' => [
                'nullable',
                'string',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'start_at' => [
                'required',
                'date',
            ],

            'end_at' => [
                'required',
                'date',
                'after:start_at',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'registration_required' => [
                'sometimes',
                'boolean',
            ],

            'registration_deadline' => [
                'nullable',
                'date',
                'before:start_at',
            ],

            'is_featured' => [
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
