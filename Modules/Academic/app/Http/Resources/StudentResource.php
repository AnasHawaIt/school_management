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
            'first_name'       => $this->user?->first_name,
            'last_name'        => $this->user?->last_name,
            'first_name_ar'    => $this->user?->first_name_ar,
            'last_name_ar'     => $this->user?->last_name_ar,
            'gender'           => $this->user?->gender,
            'date_of_birth'    => $this->user?->date_of_birth,
            'nationality'      => $this->nationality,
            'phone'            => $this->user?->phone,
            'email'            => $this->user?->email,
            'age' => $this->user?->date_of_birth ? \Carbon\Carbon::parse($this->user?->date_of_birth)->age : null,
            'national_id'     => $this->national_id,
            'blood_type'      => $this->blood_type,
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
