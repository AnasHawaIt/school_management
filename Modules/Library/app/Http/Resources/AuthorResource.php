<?php

namespace Modules\Library\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'description'=>$this->description,
            'birth_date'=>$this->birth_date,
            'death_date'=>$this->death_date,
            'created_at' => $this->created_at,
            'images'=>$this->images
        ];
    }
}
