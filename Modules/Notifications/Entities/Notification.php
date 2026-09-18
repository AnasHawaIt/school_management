<?php

namespace Modules\Notifications\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\app\Entities\User;

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
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending(Builder $query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent(Builder $query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed(Builder $query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeUnread(Builder $query)
    {
        return $query->where('is_read', false);
    }
}
