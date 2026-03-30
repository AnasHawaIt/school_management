<?php

namespace Modules\School\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id' => 'sometimes|required|exists:classes,id',
            'name' => 'sometimes|required|string|max:255',
            'max_students' => 'sometimes|required|integer|min:1',
            'current_students' => 'integer|min:0',
            'room_number' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ];
    }
}
