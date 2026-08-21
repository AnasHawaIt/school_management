<?php

namespace Modules\Transport\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plate_number' => 'required|max:255',
            'capacity' => 'required|integer|min:1',
            ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Author name is required',
            'name.unique'   => 'Author already exists',
            'capacity.required' => 'Capacity is required',
        ];
    }
}
