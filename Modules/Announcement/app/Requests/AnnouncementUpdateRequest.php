<?php

namespace Modules\Announcement\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementUpdateRequest extends FormRequest
{
    public function authorize()
    {
      //  return auth()->check();
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'is_active' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'audience' => 'sometimes|in:admin,student,teacher,parent,public',
        ];
    }

    public function messages(){
        return [
          'title.string' => 'The title must be a string.',
          'body.string' => 'The body must be a string.',
          'is_active.boolean' => 'The status must be boolean.',
          'published_at.date' => 'The published date must be a date.',
          'audience.string' => 'The audience must be a string.',
          'audience.in' => 'The audience must be admin or student or teacher or parent or public.',
        ];
    }
}
