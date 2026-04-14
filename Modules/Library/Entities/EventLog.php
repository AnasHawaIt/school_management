<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EventLog extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'event_type',
        'data'
    ];

    protected $casts = [
        'data' => 'array',
    ];

// protected static function newFactory(): EventLogFactory
    // {
    //     // return EventLogFactory::new();
    // }
}
