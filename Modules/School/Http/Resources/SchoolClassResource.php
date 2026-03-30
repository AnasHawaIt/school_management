<?php

namespace Modules\School\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolClassResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'grade_id' => $this->grade_id,
            'academic_year_id' => $this->academic_year_id,
            'name' => $this->name,
            'max_students' => $this->max_students,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'grade' => new GradeResource($this->whenLoaded('grade')),
            'academic_year' => new AcademicYearResource($this->whenLoaded('academicYear')),
            'sections' => SectionResource::collection($this->whenLoaded('sections')),
            'total_students' => $this->when(
                $this->relationLoaded('sections'),
                $this->getTotalStudents()
            ),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
