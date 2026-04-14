<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Category name is required',
            'name.string'   => 'Category name must be a string',
            'name.unique'   => 'Category already exists',
        ];
    }
}
