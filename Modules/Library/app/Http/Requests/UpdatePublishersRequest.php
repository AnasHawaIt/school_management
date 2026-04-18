<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublishersRequest extends FormRequest
{
    public function authorize()
    {
    return true;
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
            'name.string' => 'Publishers name must be a string',
            'name.unique' => 'Publishers already exists',

        ];
    }
}
