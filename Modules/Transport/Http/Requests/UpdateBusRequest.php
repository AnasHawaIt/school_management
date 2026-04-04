<?php

namespace Modules\Transport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255|unique:authors,name,' . $this->route('id'),
            'capacity' => 'sometimes|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Author name must be a string',
            'name.unique' => 'Author already exists',
            'capacity.integer' => 'Author capacity must be an integer',
        ];
    }
}
