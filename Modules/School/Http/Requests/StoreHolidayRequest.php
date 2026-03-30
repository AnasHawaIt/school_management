<?php

namespace Modules\School\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:public,academic,religious,other',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'academic_year_id.exists' => 'Selected academic year does not exist',
            'name.required' => 'Holiday name is required',
            'start_date.required' => 'Start date is required',
            'end_date.required' => 'End date is required',
            'end_date.after_or_equal' => 'End date must be after or equal to start date',
            'type.required' => 'Holiday type is required',
            'type.in' => 'Holiday type must be public, academic, religious, or other',
        ];
    }
}
