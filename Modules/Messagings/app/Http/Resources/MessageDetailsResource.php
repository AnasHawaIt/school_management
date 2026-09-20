<?php


namespace Modules\Messagings\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageDetailsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'body' => $this->body,
            'priority' => $this->priority,
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
            ],

            'images' => ImageResource::collection(
                $this->message?->images ?? collect()
            ),

            'created_at' => $this->created_at
        ];
    }
}
