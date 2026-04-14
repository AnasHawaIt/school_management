<?php

namespace Modules\Library\app\Http\Requests;

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
            'name' => 'sometimes|string|max:255|unique:authors,name',
            'description' => 'sometimes|string',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
            'birth_date'=>'sometimes|date',
            'death_date'=>'sometimes|date',
        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}
