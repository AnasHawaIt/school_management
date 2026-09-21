<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherQualificationsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'employee_id'      => $this->employee_id,
            'full_name'        => $this->full_name,
            'full_name_ar'     => $this->full_name_ar,
            'first_name'       => $this->first_name,
            'last_name'        => $this->last_name,
            'qualifications'   => $this->whenLoaded('qualifications'),
            'created_at'       => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
