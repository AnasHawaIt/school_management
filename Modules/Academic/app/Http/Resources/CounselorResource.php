<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CounselorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'counselor_id'   => $this->counselor_id,
            'specialization' => $this->specialization,
            'status'         => $this->status,
            'notes'          => $this->notes,
            'full_name'      => $this->user->first_name . ' ' . $this->user->last_name,
            'email'          => $this->user->email,
            'gender'         => $this->user->gender,
            'avatar'         => $this->user->avatar ? asset('storage/' . $this->user->avatar) : null,
            'sections'       => $this->whenLoaded('sections', fn() =>
            $this->sections->map(fn($s) => [
                'id'    => $s->id,
                'name'  => $s->name,
                'class' => $s->class?->name,
                'grade' => $s->class?->grade?->name,
            ])
            ),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
