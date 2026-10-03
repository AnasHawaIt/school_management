<?php

namespace Modules\Transport\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'  => 'required|exists:students,id',
            'route_id'   => 'required|exists:routes,id',
            'start_date' => 'sometimes|date',
            'end_date'    => 'sometimes|date|after_or_equal:borrow_date',
            //'status'      => 'nullable|in:borrowed,returned',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Student ID is required.',
            'student_id.exists' => 'Student ID does not exist.',
            'route_id.required' => 'Route ID is required.',
            'route_id.exists' => 'Route ID does not exist.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date is not a valid date.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'End date is not a valid date.',
            'end_date.after_or_equal' => 'End date is not a valid date.',
        ];
    }
}
