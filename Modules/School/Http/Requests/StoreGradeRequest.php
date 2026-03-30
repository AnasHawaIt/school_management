<?php

namespace Modules\School\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:grades,name',
            'level' => 'required|in:primary,middle,high',
            'order' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Grade name is required',
            'name.unique' => 'This grade already exists',
            'level.required' => 'Grade level is required',
            'level.in' => 'Grade level must be primary, middle, or high',
            'order.required' => 'Grade order is required',
        ];
    }
}
