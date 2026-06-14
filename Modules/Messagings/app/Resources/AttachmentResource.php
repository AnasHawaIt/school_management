<?php


namespace Modules\Messagings\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [

            'id' => $this->id,

            'file_name' =>
                $this->file_name,

            'file_url' =>
                $this->file_url
        ];
    }
}
