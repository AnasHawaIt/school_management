<?php

namespace Modules\Attendance\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentAttendanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'date'          => $this->date?->format('Y-m-d'),
            'check_in_time' => $this->check_in_time,
            'late_minutes'  => $this->late_minutes,
            'notes'         => $this->notes,

            'student' => $this->whenLoaded('student', fn() => [
                'id'         => $this->student->id,
                'student_id' => $this->student->student_id,
                'full_name'  => $this->student->user->first_name . ' ' . $this->student->user->last_name,
                'full_name_ar'=>$this->student->user->first_name_ar . ' ' . $this->student->user->last_name_ar,
                'gender'     => $this->student->user->gender,
                'avatar'     => $this->student->user->avatar
                    ? asset('storage/' . $this->student->user->avatar)
                    : null,
            ]),

            'status' => $this->whenLoaded('status', fn() => [
                'id'         => $this->status->id,
                'name'       => $this->status->name,
                'name_ar'    => $this->status->name_ar,
                'code'       => $this->status->code,
                'color'      => $this->status->color,
                'is_present' => $this->status->is_present,
            ]),

            'section' => $this->whenLoaded('section', fn() => [
                'id'   => $this->section->id,
                'name' => $this->section->name,
            ]),

            'recorder' => $this->whenLoaded('recorder', fn() => [
                'id'        => $this->recorder->id,
                'full_name' => $this->recorder->first_name . ' ' . $this->recorder->last_name,
            ]),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
