<?php

namespace Modules\Library\app\Http\Resources;

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
                'name' => $this->member->user?->full_name,
            ],

            'borrow_date' => $this->borrow_date,
            'due_date'    => $this->due_date,
            'return_date' => $this->return_date,
            'returned_at' => $this->returned_at,
            'status'      => $this->status,

            'created_at' => $this->created_at,
        ];
    }
}
