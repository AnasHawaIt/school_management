<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'name_ar'   => 'nullable|string|max:255',
            'type'      => 'required|in:percentage,fixed',
            'value'     => 'required|numeric|min:0',
            'reason'    => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
