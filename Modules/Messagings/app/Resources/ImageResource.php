<?php


namespace Modules\Messagings\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
class ImageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
        ];
    }

}
