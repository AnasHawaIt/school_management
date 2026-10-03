<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('role');

        return [
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $roleId,
            'display_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Role name is required',
            'name.unique' => 'This role name already exists',
            'display_name.required' => 'Display name is required',
            'permissions.array' => 'Permissions must be an array',
            'permissions.*.exists' => 'One or more permissions do not exist',
        ];
    }
}
