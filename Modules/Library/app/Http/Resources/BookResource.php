<?php

namespace Modules\Library\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'isbn'        => $this->isbn,
            'author' => $this->author ? [
                'id'   => $this->author->id,
                'name' => $this->author->name,
            ] : null,

            'category' => $this->category ? [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ] : null,
            'images'=>$this->images,
            'created_at'  => $this->created_at,
        ];
    }
}
