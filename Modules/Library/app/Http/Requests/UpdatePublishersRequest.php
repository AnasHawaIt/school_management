<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublishersRequest extends FormRequest
{
    public function authorize()
    {
    return auth()->check();
    }

    public function rules()
    {
    return [
        'name' => 'sometimes|string|max:255|unique:publishers,name,'
    ];
    }

    public function messages()
    {
    return [
            'name.string' => 'Publisher name must be a string',
            'name.unique' => 'Publisher already exists',

        ];
    }
}
