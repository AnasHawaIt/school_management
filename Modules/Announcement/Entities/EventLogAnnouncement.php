<?php

namespace Modules\Announcement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventLogAnnouncement extends Model
{
    use SoftDeletes;

    protected $table = 'event_logs_announcements';

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
