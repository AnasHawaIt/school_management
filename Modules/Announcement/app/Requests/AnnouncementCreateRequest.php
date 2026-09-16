<?php

namespace Modules\Announcement\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'body' => [
                'required',
                'string',
            ],

            'audience' => [
                'required',
                'in:all,admin,student,teacher,parent',
            ],

            'priority' => [
                'nullable',
                'in:normal,important,urgent',
            ],

            'status' => [
                'nullable',
                'in:draft,scheduled,published,expired,cancelled',
            ],

            'is_pinned' => [
                'nullable',
                'boolean',
            ],

            'scheduled_at' => [
                'nullable',
                'date',
                'after:now',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after:published_at',
            ],

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The announcement title is required.',
            'title.string' => 'The announcement title must be a string.',
            'title.max' => 'The announcement title may not exceed 255 characters.',

            'body.required' => 'The announcement body is required.',
            'body.string' => 'The announcement body must be a string.',

            'audience.required' => 'The audience is required.',
            'audience.in' => 'The audience must be all, admin, student, teacher, or parent.',

            'priority.in' => 'The priority must be normal, important, or urgent.',

            'status.in' => 'The status must be draft, scheduled, published, expired, or cancelled.',

            'is_pinned.boolean' => 'The pinned value must be true or false.',

            'scheduled_at.date' => 'The scheduled date must be a valid date.',
            'scheduled_at.after' => 'The scheduled date must be in the future.',

            'published_at.date' => 'The published date must be a valid date.',

            'expires_at.date' => 'The expiration date must be a valid date.',
            'expires_at.after' => 'The expiration date must be after the published date.',

            'images.array' => 'Images must be provided as an array.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.max' => 'Each image may not exceed 5 MB.',
        ];
    }
}
