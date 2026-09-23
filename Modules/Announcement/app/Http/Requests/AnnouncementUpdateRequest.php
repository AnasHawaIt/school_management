<?php

namespace Modules\Announcement\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'body' => [
                'sometimes',
                'string',
            ],

            'audience' => [
                'sometimes',
                'in:all,admin,student,teacher,parent',
            ],

            'priority' => [
                'sometimes',
                'in:normal,important,urgent',
            ],

            'is_pinned' => [
                'sometimes',
                'boolean',
            ],

            'scheduled_at' => [
                'sometimes',
                'nullable',
                'date',
            ],

            'published_at' => [
                'sometimes',
                'nullable',
                'date',
            ],

            'expires_at' => [
                'sometimes',
                'nullable',
                'date',
            ],

            'images' => [
                'sometimes',
                'nullable',
                'array',
            ],

            'images.*' => [
                'image',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not exceed 255 characters.',

            'body.string' => 'The body must be a string.',

            'audience.in' => 'The audience must be all, admin, student, teacher, or parent.',

            'priority.in' => 'The priority must be normal, important, or urgent.',

            'is_pinned.boolean' => 'The pinned value must be true or false.',

            'scheduled_at.date' => 'The scheduled date must be a valid date.',

            'published_at.date' => 'The published date must be a valid date.',

            'expires_at.date' => 'The expiration date must be a valid date.',

            'images.array' => 'Images must be provided as an array.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.max' => 'Each image may not exceed 5 MB.',
        ];
    }
}
