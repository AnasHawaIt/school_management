<?php

namespace Modules\Messagings\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'body' => $this->body,
            'priority' => $this->priority,
            'sender_id' => $this->sender_id,
            'created_at' => $this->created_at
                ->format('Y-m-d H:i:s')
        ];
    }
}
