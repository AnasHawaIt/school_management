<?php

namespace Modules\Notifications\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Notifications\Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\User;

class Notification extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'notification';

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'type',
        'data',
        'is_read',
        'read_at',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
