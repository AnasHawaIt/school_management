<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id',
            'membership_number' => 'sometimes|unique:members,membership_number',
        ];
    }

    public function messages(): array
    {
        return [
            'membership_number.unique' => 'Membership number already exists',
            'user_id.exists' => 'User id does not exist',
            ];
    }
}
