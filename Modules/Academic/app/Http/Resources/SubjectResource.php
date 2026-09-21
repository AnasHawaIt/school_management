<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'code'         => $this->code,
            'name'         => $this->name,
            'name_ar'      => $this->name_ar,
            'description'  => $this->description,
            'grade'        => $this->whenLoaded('grade', fn() => [
                'id'    => $this->grade->id,
                'name'  => $this->grade->name,
            ]),
            'weekly_hours' => $this->weekly_hours,
            'credit_hours' => $this->credit_hours,
            'pass_mark'    => $this->pass_mark,
            'full_mark'    => $this->full_mark,
            'is_mandatory' => $this->is_mandatory,
            'color'        => $this->color,
            'status'       => $this->status,
            'teachers'     => $this->whenLoaded('teachers', fn() =>
            $this->teachers->map(fn($t) => [
                'id'           => $t->id,
                'full_name'    => $t->full_name,
                'section_id'   => $t->pivot->section_id,
                'academic_year_id' => $t->pivot->academic_year_id,
            ])
            ),
            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
