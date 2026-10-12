<?php

namespace Modules\Examination\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkEnterResultsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'results'                   => 'required|array|min:1',
            'results.*.student_id'      => 'required|exists:students,id',
            'results.*.marks_obtained'  => 'nullable|numeric|min:0',
            'results.*.is_absent'       => 'nullable|boolean',
            'results.*.remarks'         => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'results.*.marks_obtained.min' => 'Marks cannot be negative.',
        ];
    }
}
