<?php

namespace Modules\Attendance\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'requestable_type' => 'required|in:Modules\Academic\Entities\Student,Modules\Academic\Entities\Teacher',
            'requestable_id'   => 'required|integer',
            'type'             => 'required|in:sick,personal,emergency,other',
            'from_date'        => 'required|date',
            'to_date'          => 'required|date|after_or_equal:from_date',
            'reason'           => 'required|string|max:1000',
            'attachment'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}
