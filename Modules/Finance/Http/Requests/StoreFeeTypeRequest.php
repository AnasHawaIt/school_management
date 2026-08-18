<?php
namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'name_ar'      => 'nullable|string|max:255',
            'code'         => 'required|string|max:50|unique:fee_types,code',
            'category'     => 'required|in:tuition,books,transport,activity,uniform,other',
            'description'  => 'nullable|string',
            'is_recurring' => 'boolean',
            'is_active'    => 'boolean',
        ];
    }
}
