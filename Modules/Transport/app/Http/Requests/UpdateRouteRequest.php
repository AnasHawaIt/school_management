<?php

namespace Modules\Transport\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'       => 'sometimes|string|max:255',
            'bus_id'  => 'required|exists:buses,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Route name is required',
            'bus_id.required' => 'Bus is required',
            'Bus_id.exists' => 'Bus not found',
        ];
    }

}
