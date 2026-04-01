<?php

namespace Modules\Announcement\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementDeleteRequest extends FormRequest
{
    public function authorize()
    {
        //return auth()->check();
        return true;
    }

    public function rules()
    {
        return [

            'user_id' => 'required|integer|exists:announcements,id',
        ];
    }

    public function messages()
    {
        return [

            'user_id.required' => ' this Announcement is not found ',
        ];
    }
}
