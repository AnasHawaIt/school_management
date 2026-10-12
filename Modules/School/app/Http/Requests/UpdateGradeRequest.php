<?php

namespace Modules\School\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => 'sometimes|required|string|max:255|unique:grades,name,' . $id,
            'level' => 'sometimes|required|in:primary,middle,high',
            'order' => 'sometimes|required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
