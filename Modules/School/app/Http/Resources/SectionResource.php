<?php

namespace Modules\School\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'class_id' => $this->class_id,
            'name' => $this->name,
            'max_students' => $this->max_students,
            'current_students' => $this->current_students,
            'available_seats' => $this->getAvailableSeats(),
            'room_number' => $this->room_number,
            'is_active' => $this->is_active,
            'class' => new SchoolClassResource($this->whenLoaded('class')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
