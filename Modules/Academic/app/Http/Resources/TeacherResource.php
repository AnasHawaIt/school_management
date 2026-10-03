<?php

namespace Modules\Academic\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'employee_id'      => $this->employee_id,
            'first_name'       => $this->user?->first_name,
            'last_name'        => $this->user?->last_name,
            'first_name_ar'    => $this->user?->first_name_ar,
            'last_name_ar'     => $this->user?->last_name_ar,
            'gender'           => $this->user?->gender,
            'date_of_birth'    => $this->user?->date_of_birth,
            'age' => $this->user?->date_of_birth ? \Carbon\Carbon::parse($this->user?->date_of_birth)->age : null,            'national_id'      => $this->national_id,
            'nationality'      => $this->nationality,
            'phone'            => $this->user?->phone,
            'email'            => $this->user?->email,
            'address'          => $this->address,
            'city'             => $this->city,
            'specialization'   => $this->specialization,
            'experience_years' => $this->experience_years,
            'joining_date'     => $this->joining_date?->format('Y-m-d'),
            'contract_type'    => $this->contract_type,
            'status'           => $this->status,
            'notes'            => $this->notes,
            'qualifications'   => $this->whenLoaded('qualifications'),
            'subjects'         => $this->whenLoaded('subjects'),
            'timetables'       => $this->whenLoaded('timetables'),
            'created_at'       => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
