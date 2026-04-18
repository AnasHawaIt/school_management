<?php

namespace Modules\Attendance\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherAttendanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'date'           => $this->date?->format('Y-m-d'),
            'check_in_time'  => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'late_minutes'   => $this->late_minutes,
            'notes'          => $this->notes,

            // البيانات الشخصية عبر teacher->user
            'teacher' => $this->whenLoaded('teacher', fn() => [
                'id'          => $this->teacher->id,
                'employee_id' => $this->teacher->employee_id,
                'full_name'   => $this->teacher->user->first_name . ' ' . $this->teacher->user->last_name,
                'gender'      => $this->teacher->user->gender,
                'avatar'      => $this->teacher->user->avatar
                    ? asset('storage/' . $this->teacher->user->avatar)
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

            'recorder' => $this->whenLoaded('recorder', fn() => [
                'id'        => $this->recorder->id,
                'full_name' => $this->recorder->first_name . ' ' . $this->recorder->last_name,
            ]),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
