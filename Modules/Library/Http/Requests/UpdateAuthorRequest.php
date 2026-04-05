<?php

namespace Modules\Library\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255|unique:authors,name,' . $this->route('id'),
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Author name must be a string',
            'name.unique' => 'Author already exists',
        ];
    }
}
