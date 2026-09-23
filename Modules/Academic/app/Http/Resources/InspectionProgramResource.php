<?php

namespace Modules\Academic\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InspectionProgramResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'title_ar'        => $this->title_ar,
            'inspection_date' => $this->inspection_date?->format('Y-m-d'),
            'start_time'      => $this->start_time,
            'end_time'        => $this->end_time,
            'type'            => $this->type,
            'status'          => $this->status,
            'objectives'      => $this->objectives,
            'is_current'      =>$this->is_current,
            'notes'           => $this->notes,

            'section' => $this->whenLoaded('section', fn() => [
                'id'    => $this->section->id,
                'name'  => $this->section->name,
                'class' => $this->section->class?->name,
                'grade' => $this->section->class?->grade?->name,
            ]),

            'semester' => $this->whenLoaded('semester', fn() => [
                'id'   => $this->semester->id,
                'name' => $this->semester->name,
            ]),

            'counselors' => $this->whenLoaded('counselors', fn() =>
            $this->counselors->map(fn($c) => [
                'id'           => $c->id,
                'counselor_id' => $c->counselor_id,
                'full_name'    => $c->user->first_name . ' ' . $c->user->last_name,
                'role'         => $c->pivot->role,
                'observation'  => $c->pivot->observation,
                'result'       => $c->pivot->result,
                'user_id'      => $c->user_id,
            ])
            ),

            'creator' => $this->whenLoaded('creator', fn() => [
                'id'        => $this->creator->id,
                'full_name' => $this->creator->first_name . ' ' . $this->creator->last_name,
            ]),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
