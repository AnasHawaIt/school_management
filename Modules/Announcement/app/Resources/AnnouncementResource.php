<?php

namespace Modules\Announcement\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'body'=>$this->body,
            'user_id'=>$this->user_id,
            'is_active'=>$this->is_active,
            'published_at'=>$this->published_at
        ];
    }
}
