<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'due_days' => ['nullable', 'integer', 'min:0', 'max:365'],
        ];
    }
}
