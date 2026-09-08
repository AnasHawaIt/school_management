<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'library_reservations';

    protected $fillable = ['book_id', 'member_id', 'status', 'fulfilled_at'];

    protected $casts = ['fulfilled_at' => 'datetime'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
