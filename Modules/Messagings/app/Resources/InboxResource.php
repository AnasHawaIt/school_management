<?php

namespace Modules\Messagings\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InboxResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message_id' => $this->message?->id,
            'subject' => $this->message?->subject,

            'sender' => [
                'id' => $this->message?->sender?->id,
                'name' => $this->message?->sender?->name,
            ],

            'images' => ImageResource::collection(
                $this->message?->images ?? collect()
            ),

            'is_read' => $this->is_read,
            'read_at' => $this->read_at,
            'created_at' => $this->message?->created_at,
        ];
    }
}
