<?php

namespace Modules\Transport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Transport\Database\Factories\EventLogFactory;

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
