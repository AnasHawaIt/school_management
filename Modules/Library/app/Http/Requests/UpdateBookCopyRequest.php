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
                'required',
                'string',
                'max:255',
                Rule::unique('library_copies', 'barcode')->ignore($this->route('copy')),
            ],
            'status' => 'sometimes|in:available,borrowed,lost,damaged,maintenance',
            'location' => 'nullable|string|max:255',
        ];
    }
}
