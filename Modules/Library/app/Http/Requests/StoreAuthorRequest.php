<?php

namespace Modules\Library\app\Http\Requests;

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
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
            'birth_date'=>'nullable|date',
            'death_date'=>'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Author name is required',
            'name.unique'   => 'Author already exists',
            'description.string' => 'Author description is required',
            'death_date.date' => 'Author death date is required',
            'birth_date.date' => 'Author birth date is required',
            'photo.image' => 'Author photo is required',
        ];
    }
}
