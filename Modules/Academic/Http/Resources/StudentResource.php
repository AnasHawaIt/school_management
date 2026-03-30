<?php

namespace Modules\Academic\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'student_id'      => $this->student_id,
            'full_name'       => $this->full_name,
            'full_name_ar'    => $this->full_name_ar,
            'first_name'      => $this->first_name,
            'last_name'       => $this->last_name,
            'gender'          => $this->gender,
            'age'             => $this->age,
            'date_of_birth'   => $this->date_of_birth?->format('Y-m-d'),
            'national_id'     => $this->national_id,
            'nationality'     => $this->nationality,
            'blood_type'      => $this->blood_type,
            'phone'           => $this->phone,
            'email'           => $this->user?->email,
            'address'         => $this->address,
            'city'            => $this->city,
            'photo'           => $this->photo ? asset('storage/' . $this->photo) : null,
            'enrollment_date' => $this->enrollment_date?->format('Y-m-d'),
            'previous_school' => $this->previous_school,
            'status'          => $this->status,
            'notes'           => $this->notes,
            'section'         => $this->whenLoaded('section', fn() => [
                'id'    => $this->section->id,
                'name'  => $this->section->name,
                'class' => $this->section->class?->name,
                'grade' => $this->section->class?->grade?->name,
            ]),
            'academic_year'  => $this->whenLoaded('academicYear', fn() => [
                'id'   => $this->academicYear->id,
                'name' => $this->academicYear->name,
            ]),
            'parents'        => $this->whenLoaded('parents', fn() =>
            $this->parents->map(fn($p) => [
                'id'                 => $p->id,
                'full_name'          => $p->full_name,
                'phone'              => $p->phone,
                'relationship'       => $p->pivot->relationship,
                'is_primary_contact' => (bool) $p->pivot->is_primary_contact,
                'can_pickup'         => (bool) $p->pivot->can_pickup,
            ])
            ),
            'medical_record' => $this->whenLoaded('medicalRecord'),
            'created_at'     => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
