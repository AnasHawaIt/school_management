<?php

namespace Modules\Announcement\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function with($request): array
    {
        return [
            'success' => true,
            'message' => 'Announcement retrieved successfully.',
        ];
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,

            'created_by' => $this->created_by,

            'audience' => $this->audience?->value,
            'status' => $this->status?->value,
            'priority' => $this->priority?->value,

            'is_pinned' => $this->is_pinned,

            'scheduled_at' => $this->scheduled_at?->toISOString(),
            'published_at' => $this->published_at?->toISOString(),
            'expires_at' => $this->expires_at?->toISOString(),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
