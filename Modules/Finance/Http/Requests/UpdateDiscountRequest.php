<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'sometimes|required|string|max:255',
            'name_ar'   => 'nullable|string|max:255',
            'type'      => 'sometimes|required|in:percentage,fixed',
            'value'     => 'sometimes|required|numeric|min:0',
            'reason'    => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
