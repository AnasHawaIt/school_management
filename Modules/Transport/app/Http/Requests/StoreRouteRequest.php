<?php

namespace Modules\Transport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */

    public function rules()
    {
        return [
            'name'       => 'required|string|max:255',
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

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
