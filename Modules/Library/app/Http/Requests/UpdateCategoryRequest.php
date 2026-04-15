<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
    return true;
    }

    public function rules()
    {
    return [
        'name' => 'sometimes|string|max:255|unique:categories,name,' . $this->route('id'),
        'description' => 'sometimes|string'
    ];
    }

    public function messages()
    {
    return [
            'name.string' => 'Category name must be a string',
            'name.unique' => 'Category already exists',
            'description.string' => 'Category description must be a string',
        ];
    }
}
