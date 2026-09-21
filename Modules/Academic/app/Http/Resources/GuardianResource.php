<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuardianResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'full_name'       => $this->full_name,
            'first_name'       => $this->user?->first_name,
            'last_name'        => $this->user?->last_name,
            'first_name_ar'    => $this->user?->first_name_ar,
            'last_name_ar'     => $this->user?->last_name_ar,
            'gender'           => $this->user?->gender,
            'date_of_birth'    => $this->user?->date_of_birth,
            'age' => $this->user?->date_of_birth ? \Carbon\Carbon::parse($this->user?->date_of_birth)->age : null,            'national_id'      => $this->national_id,
            'phone'            => $this->user?->phone,
            'email'            => $this->user?->email,
            'national_id'     => $this->national_id,
            'phone_secondary' => $this->phone_secondary,
            'address'         => $this->address,
            'city'            => $this->city,
            'occupation'      => $this->occupation,
            'employer'        => $this->employer,
            'education_level' => $this->education_level,
            'status'          => $this->status,
            'students'        => $this->whenLoaded('students', fn() =>
            $this->students->map(fn($s) => [
                'id'                 => $s->id,
                'full_name'          => $s->full_name,
                'student_id'         => $s->student_id,
                'relationship'       => $s->pivot->relationship,
                'is_primary_contact' => (bool) $s->pivot->is_primary_contact,
                'can_pickup'         => (bool) $s->pivot->can_pickup,
            ])
            ),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
