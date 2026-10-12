<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeeTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name'         => 'sometimes|required|string|max:255',
            'name_ar'      => 'nullable|string|max:255',
            'code'         => ['sometimes', 'required', 'string', 'max:50', Rule::unique('fee_types', 'code')->ignore($id)],
            'category'     => 'sometimes|required|in:tuition,books,transport,activity,uniform,other',
            'description'  => 'nullable|string',
            'is_recurring' => 'boolean',
            'is_active'    => 'boolean',
        ];
    }
}
