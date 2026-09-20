<?php

namespace Modules\SMS\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Announcement\app\Entities\Announcement;

// use Modules\SMS\Database\Factories\SmsLogFactory;

class SmsLog extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'announcement_id',
        'phone',
        'message',
        'status',
        'response',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    // protected static function newFactory(): SmsLogFactory
    // {
    //     // return SmsLogFactory::new();
    // }
}
