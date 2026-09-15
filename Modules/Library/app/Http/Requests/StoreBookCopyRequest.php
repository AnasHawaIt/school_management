<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookCopyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'barcode' => [
                'required',
                'string',
                'max:255',
                'unique:library_copies,barcode',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'replacement_cost' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }
}
