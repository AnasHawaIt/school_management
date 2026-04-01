<?php

namespace Modules\Announcement\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementCreateRequest extends FormRequest
{
    public function authorize()
    {
        //return auth()->check(); // السماح لأي مستخدم مسجل، يمكن تخصيصه
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
        ];
    }

    public function messages()
    {
        return [
            'title.required' => ' title => required',
            'message.required' => ' message => required',
        ];
    }
}
