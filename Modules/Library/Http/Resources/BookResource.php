<?php

namespace Modules\Library\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'isbn'        => $this->isbn,
            'copies'      => $this->copies,
            'author'      => [
                'id'   => $this->author->id,
                'name' => $this->author->name,
            ],
            'category'    => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ],
            'created_at'  => $this->created_at,
        ];
    }
}
