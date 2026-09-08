<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookCopyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'barcode' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('library_copies', 'barcode')->ignore($this->route('copy')),
            ],
            'status' => 'sometimes|in:available,borrowed,lost,damaged,maintenance',
            'location' => 'nullable|string|max:255',
            'replacement_cost' => 'sometimes|nullable|numeric|min:0',
        ];
    }
}
