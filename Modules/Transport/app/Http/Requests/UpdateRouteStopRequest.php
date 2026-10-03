<?php

namespace Modules\Transport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteStopRequest extends FormRequest
{
    public function authorize()
    {
    return true;
    }

    public function rules()
    {
        return [
            'stop_name' => 'sometimes|string|max:255|unique:categories,name',
            'sequence' => 'sometimes|integer|min:1',
            'route_id'   => 'required|exists:routes,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Category name is required',
            'name.string'   => 'Category name must be a string',
            'name.unique'   => 'Category already exists',
            'sequence.required' => 'Sequence is required',
            'sequence.integer' => 'Sequence must be an integer',
            'sequence.min' => 'Sequence must be at least 1',
            'route_id.required' => 'Route is required',
            'route_id.integer' => 'Route must be an integer',
        ];
    }
}
