<?php

namespace Modules\Announcement\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementCreateRequest extends FormRequest
{
    public function authorize()
    {

        return true;
    }

    public function rules()
    {
        return [
                'title' => 'required|string|max:255',
                'body' => 'required|string',
                'user_id' => 'required|exists:users,id',
                'is_active' => 'boolean',
                'published_at' => 'nullable|date',
                'audience' => 'sometimes|in:admin,student,teacher,parent,public',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Message title is required.',
            'body.required' => 'Body is required.',
            'user_id.required' => 'User is required.',
            'audience.required' => 'Audience is required.',
            'audience.in' => 'Audience must be admin or student or teacher or parent or public.',
        ];
    }
}
