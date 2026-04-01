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
        ];
    }
}
