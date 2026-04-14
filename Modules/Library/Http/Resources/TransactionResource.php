<?php

namespace Modules\Library\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'book' => [
                'id'    => $this->book->id,
                'title' => $this->book->title,
            ],

            'member' => [
                'id'   => $this->member->id,
                'name' => $this->member->name,
            ],

            'borrow_date' => $this->borrow_date,
            'due_date'    => $this->due_date,
            'status'      => $this->status,

            'created_at' => $this->created_at,
        ];
    }
}
