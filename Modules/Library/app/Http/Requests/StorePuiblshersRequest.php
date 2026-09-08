<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePuiblshersRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:publishers,name',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Publishers name is required',
            'name.string'   => 'Publishers name must be a string',
            'name.unique'   => 'Publishers already exists',
        ];
    }
}
