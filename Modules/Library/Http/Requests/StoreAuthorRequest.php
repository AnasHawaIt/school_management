<?php

namespace Modules\Library\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:authors,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Author name is required',
            'name.unique'   => 'Author already exists',
        ];
    }
}
