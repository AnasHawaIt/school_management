<?php

namespace Modules\Core\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name'=>$this->last_name,
            'first_name_ar' => $this->first_name_ar,
            'last_name_ar' => $this->last_name_ar,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth->format('Y-m-d'),
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'user_type' => $this->user_type,
            'national_id'=>$this->user?->national_id,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at?->format('Y-m-d H:i:s'),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => $this->when(
                $this->relationLoaded('roles'),
                function () {
                    return $this->getPermissions()->pluck('name');
                }
            ),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
