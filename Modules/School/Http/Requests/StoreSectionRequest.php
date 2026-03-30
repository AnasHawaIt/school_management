<?php

namespace Modules\School\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'max_students' => 'required|integer|min:1',
            'current_students' => 'integer|min:0',
            'room_number' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'class_id.required' => 'Class is required',
            'class_id.exists' => 'Selected class does not exist',
            'name.required' => 'Section name is required',
            'max_students.required' => 'Maximum students is required',
            'max_students.min' => 'Maximum students must be at least 1',
            'current_students.min' => 'Current students cannot be negative',
        ];
    }
}
