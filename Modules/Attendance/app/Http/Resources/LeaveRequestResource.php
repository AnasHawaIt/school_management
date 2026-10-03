<?php

namespace Modules\Attendance\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'from_date'  => $this->from_date?->format('Y-m-d'),
            'to_date'    => $this->to_date?->format('Y-m-d'),
            'days_count' => $this->days_count,
            'reason'     => $this->reason,
            'attachment' => $this->attachment
                ? asset('storage/' . $this->attachment)
                : null,
            'status'      => $this->status,
            'admin_notes' => $this->admin_notes,

            // الشخص اللي طلب الإجازة (student أو teacher) — بياناته عبر user
            'requestable' => $this->whenLoaded('requestable', fn() => [
                'id'       => $this->requestable->id,
                'type'     => class_basename($this->requestable_type),
                'full_name' => $this->requestable->user->first_name
                    . ' ' . $this->requestable->user->last_name,
                'avatar'   => $this->requestable->user->avatar
                    ? asset('storage/' . $this->requestable->user->avatar)
                    : null,
            ]),

            'reviewer' => $this->whenLoaded('reviewer', fn() => [
                'id'        => $this->reviewer->id,
                'full_name' => $this->reviewer->first_name . ' ' . $this->reviewer->last_name,
            ]),

            'creator' => $this->whenLoaded('creator', fn() => [
                'id'        => $this->creator->id,
                'full_name' => $this->creator->first_name . ' ' . $this->creator->last_name,
            ]),

            'reviewed_at' => $this->reviewed_at?->format('Y-m-d H:i:s'),
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
