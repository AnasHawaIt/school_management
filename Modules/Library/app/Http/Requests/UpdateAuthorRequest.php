<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {

        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'birth_date'=>'sometimes|date',
            'death_date'=>'sometimes|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Name already exists',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name cannot be longer than 255 characters',
            'description.string' => 'Description must be a string',
            'birth_date.date'=>'Birth date must be a date',
            'death_date.date'=>'Death date must be a date',
            'photo.image'=>'Photo is not valid',
        ];
    }
}
