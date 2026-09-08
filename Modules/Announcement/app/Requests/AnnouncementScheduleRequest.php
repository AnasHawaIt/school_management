<?php

namespace Modules\Announcement\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scheduled_at' => [
                'required',
                'date',
                'after:now',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'scheduled_at.required' => 'The scheduled date is required.',
            'scheduled_at.date' => 'The scheduled date must be a valid date.',
            'scheduled_at.after' => 'The scheduled date must be in the future.',
        ];
    }
}
