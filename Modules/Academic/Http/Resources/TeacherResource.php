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
            'full_name'        => $this->full_name,
            'full_name_ar'     => $this->full_name_ar,
            'first_name'       => $this->first_name,
            'last_name'        => $this->last_name,
            'gender'           => $this->gender,
            'date_of_birth'    => $this->date_of_birth?->format('Y-m-d'),
            'national_id'      => $this->national_id,
            'nationality'      => $this->nationality,
            'phone'            => $this->phone,
            'email'            => $this->user?->email,
            'address'          => $this->address,
            'city'             => $this->city,
            'photo'            => $this->photo ? asset('storage/' . $this->photo) : null,
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
