<?php namespace Modules\Academic\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'code'         => 'required|string|max:20|unique:subjects,code',
            'name'         => 'required|string|max:150',
            'name_ar'      => 'nullable|string|max:150',
            'description'  => 'nullable|string',
            'grade_id'     => 'required|exists:grades,id',
            'weekly_hours' => 'nullable|integer|min:1|max:30',
            'credit_hours' => 'nullable|integer|min:1|max:10',
            'pass_mark'    => 'nullable|numeric|min:0|max:100',
            'full_mark'    => 'nullable|numeric|min:1|max:100',
            'is_mandatory' => 'nullable|boolean',
            'color'        => 'nullable|string|max:20',
        ];
    }
}
